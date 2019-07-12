<?php


namespace App\Service;


use App\Jobs\IndentSettlement;
use App\Models\Indent\IndentInfo;
use App\Models\SystemSetting;
use App\Models\Up\Runwater;
use App\Models\Up\Wallet;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class Transaction
{
    // 需求文档
    public static function addDemandFile($indentData, $demand_file)
    {
        $indentData->demand_file = $demand_file;
        $re                      = $indentData->save();
        if (!$re)
            throw new Exception('操作失败');

        return true;
    }

    // 支付购买
    public static function pay($indent_num)
    {
        DB::transaction(function () use ($indent_num) {
            try {
                // 订单数据
                $indentData = IndentInfo::whereIndentNum($indent_num)->lockForUpdate()->first();
                // 检查订单状态
                Pub::checkParm($indentData->status, IndentInfo::STATUS['待付款'], '订单状态错误');
                // 检测订单归属
                IndentInfo::checkIndentBelong([$indentData->buyer_id]);
                // 校验钱包状态
                Wallet::checkStatus($indentData->buyer_id, Wallet::STATUS['启用']);
                // 校验修改校验锁
                Wallet::checkChangLock($indentData->buyer_id);
                // 钱包余额是够足够
                Wallet::hasEnoughMoney($indentData->indent_amount);

                $time = date('Y-m-d H:i:s');
                $M    = $indentData->indent_amount;
                $U    = $indentData->buyer_id;
                // 公共钱包资金增加
                $centerMoney = Wallet::whereUid(Wallet::CENTERID)->value('available_money') + $M;
                Wallet::whereUid(Wallet::CENTERID)->update([
                    'available_money' => $centerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock(Wallet::CENTERID, $centerMoney, $time)
                ]);

                // 买家钱包资金扣除
                $buyerMoney = Wallet::whereUid($U)->lockForUpdate()->value('available_money') - $M;
                Wallet::whereUid($U)->update([
                    'available_money' => $buyerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock($U, $buyerMoney, $time)
                ]);

                // 生成交易流水
                $key         = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
                $runwaterNum = createRunwaterNum($key);
                Runwater::create([
                    'runwater_num' => $runwaterNum,
                    'from_uid'     => $U,
                    'to_uid'       => Wallet::CENTERID,
                    'indent_id'    => $indentData->indent_id,
                    'indent_num'   => $indentData->indent_num,
                    'type'         => Runwater::TYPE['订单付款'],
                    'direction'    => Runwater::DIRECTION['转出'],
                    'money'        => $M,
                    'status'       => Runwater::STATUS['成功']
                ]);
                Cache::increment($key);

                // 修改订单信息
                $indentData->status     = IndentInfo::STATUS['已付款待接单'];
                $indentData->pay_amount = $M;
                $indentData->pay_time   = $time;
                $indentData->save();
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        });

        return true;
    }

    // 待接单取消订单/卖家拒单资金操作，录入取消原因
    public static function fullRefundToBuyer($indentData, $cancelCause)
    {
        DB::transaction(function () use ($indentData, $cancelCause) {
            try {
                $time = date('Y-m-d H:i:s');
                $M    = $indentData->indent_amount;
                $U    = $indentData->buyer_id;

                // 公共钱包资金减少
                $centerMoney = Wallet::whereUid(Wallet::CENTERID)->lockForUpdate()->value('available_money') - $M;
                Wallet::whereUid(Wallet::CENTERID)->update([
                    'available_money' => $centerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock(Wallet::CENTERID, $centerMoney, $time)
                ]);

                // 买家家钱包资金增加
                $buyerMoney = Wallet::whereUid($U)->lockForUpdate()->value('available_money') + $M;
                Wallet::whereUid($U)->update([
                    'available_money' => $buyerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock($U, $buyerMoney, $time)
                ]);

                // 生成交易流水
                $key         = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
                $runwaterNum = createRunwaterNum($key);
                Runwater::create([
                    'runwater_num' => $runwaterNum,
                    'from_uid'     => Wallet::CENTERID,
                    'to_uid'       => $U,
                    'indent_id'    => $indentData->indent_id,
                    'indent_num'   => $indentData->indent_num,
                    'type'         => Runwater::TYPE['取消订单全额退款'],
                    'direction'    => Runwater::DIRECTION['转入'],
                    'money'        => $M,
                    'status'       => Runwater::STATUS['成功']
                ]);
                Cache::increment($key);

                // 修改订单信息
                $indentData->status       = (JWTAuth::user()->uid == $indentData->buyer_id) ? IndentInfo::STATUS['待接单买家取消订单'] : IndentInfo::STATUS['卖家拒单'];
                $indentData->cancel_cause = $cancelCause;
                $indentData->save();
            } catch (\Exception $e) {
                throw new Exception('操作失败');
            }
        });

        return true;
    }

    // 卖家支付赔偿保证费
    public static function payCompensateFee($indentData)
    {
        DB::transaction(function () use ($indentData) {
            try {
                $time = date('Y-m-d H:i:s');
                $M    = $indentData->compensate_fee;
                $U    = $indentData->seller_id;

                // 公共钱包资金增加
                $centerMoney = Wallet::whereUid(Wallet::CENTERID)->lockForUpdate()->value('available_money') + $M;
                Wallet::whereUid(Wallet::CENTERID)->update([
                    'available_money' => $centerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock(Wallet::CENTERID, $centerMoney, $time)
                ]);

                // 卖家钱包资金扣除
                $sellerMoney = Wallet::whereUid($U)->lockForUpdate()->value('available_money') - $M;
                Wallet::whereUid($U)->update([
                    'available_money' => $sellerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock($U, $sellerMoney, $time)
                ]);

                // 生成交易流水
                $key         = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
                $runwaterNum = createRunwaterNum($key);
                Runwater::create([
                    'runwater_num' => $runwaterNum,
                    'from_uid'     => $U,
                    'to_uid'       => Wallet::CENTERID,
                    'indent_id'    => $indentData->indent_id,
                    'indent_num'   => $indentData->indent_num,
                    'type'         => Runwater::TYPE['支付赔偿保证费'],
                    'direction'    => Runwater::DIRECTION['转出'],
                    'money'        => $M,
                    'status'       => Runwater::STATUS['成功']
                ]);
                Cache::increment($key);

                // 修改订单信息
                $indentData->status = IndentInfo::STATUS['交易中'];
                $indentData->save();
            } catch (\Exception $e) {
                throw new Exception('操作失败');
            }
        });

        return true;
    }

    // 交易中买家取消订单资金操作
    public static function inTransactionBuyerCancelMoneyOP($indentData, $cancelCause)
    {
        DB::transaction(function () use ($indentData, $cancelCause) {
            try {
                $time = date('Y-m-d H:i:s');
                // 赔偿保证费
                $C = $indentData->compensate_fee;
                // 支出赔偿
                $expendC = floor($C * SystemSetting::whereSettingName('userbtain_compensate_ratio')->value('value'));
                // 卖家获得资金 (自身抵押赔偿费+ 分得买家赔偿费）
                $sellerM = $C + $expendC;
                // 买家获得资金 (购买资金 - 赔偿保证费)
                $buyerM = $indentData->indent_amount - $C;
                // 公共钱包退还
                $centerM = $buyerM + $sellerM;

                // 公共钱包资金减少
                $centerMoney = Wallet::whereUid(Wallet::CENTERID)->lockForUpdate()->value('available_money') - $centerM;
                Wallet::whereUid(Wallet::CENTERID)->update([
                    'available_money' => $centerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock(Wallet::CENTERID, $centerMoney, $time)
                ]);

                // 买家钱包资金增加
                $buyerMoney = Wallet::whereUid($indentData->buyer_id)->lockForUpdate()->value('available_money') + $buyerM;
                Wallet::whereUid($indentData->buyer_id)->update([
                    'available_money' => $buyerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock($indentData->buyer_id, $buyerMoney, $time)
                ]);

                // 生成交易流水
                $key         = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
                $runwaterNum = createRunwaterNum($key);
                Runwater::create([
                    'runwater_num' => $runwaterNum,
                    'from_uid'     => Wallet::CENTERID,
                    'to_uid'       => $indentData->buyer_id,
                    'indent_id'    => $indentData->indent_id,
                    'indent_num'   => $indentData->indent_num,
                    'type'         => Runwater::TYPE['取消订单非全额退款'],
                    'direction'    => Runwater::DIRECTION['转入'],
                    'money'        => $buyerM,
                    'status'       => Runwater::STATUS['成功']
                ]);
                Cache::increment($key);

                // 卖家钱包资金增加
                $sellerMoney = Wallet::whereUid($indentData->seller_id)->lockForUpdate()->value('available_money') + $sellerM;
                Wallet::whereUid($indentData->seller_id)->update([
                    'available_money' => $sellerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock($indentData->seller_id, $sellerMoney, $time)
                ]);

                // 生成交易流水
                $key         = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
                $runwaterNum = createRunwaterNum($key);
                Runwater::create([
                    'runwater_num' => $runwaterNum,
                    'from_uid'     => Wallet::CENTERID,
                    'to_uid'       => $indentData->seller_id,
                    'indent_id'    => $indentData->indent_id,
                    'indent_num'   => $indentData->indent_num,
                    'type'         => Runwater::TYPE['对方取消订单退款'],
                    'direction'    => Runwater::DIRECTION['转入'],
                    'money'        => $sellerM,
                    'status'       => Runwater::STATUS['成功']
                ]);
                Cache::increment($key);

                // 修改订单信息
                $indentData->status       = IndentInfo::STATUS['交易中买家取消订单'];
                $indentData->cancel_cause = $cancelCause;
                $indentData->save();
            } catch (\Exception $e) {
                throw new Exception('操作失败');
            }
        });

        return true;
    }

    // 交易中卖家取消订单资金操作
    public static function inTransactionSellerCancelMoneyOP($indentData, $cancelCause)
    {
        DB::transaction(function () use ($indentData, $cancelCause) {
            try {
                $time = date('Y-m-d H:i:s');
                // 赔偿保证费
                $C = $indentData->compensate_fee;
                // 支出赔偿
                $expendC = floor($C * SystemSetting::whereSettingName('userbtain_compensate_ratio')->value('value'));
                // 买家获得资金 (购买资金 + 分得卖家赔偿费)
                $buyerM = $indentData->indent_amount + $expendC;
                // 公共钱包退还
                $centerM = $buyerM;

                // 公共钱包资金减少
                $centerMoney = Wallet::whereUid(Wallet::CENTERID)->lockForUpdate()->value('available_money') - $centerM;
                Wallet::whereUid(Wallet::CENTERID)->update([
                    'available_money' => $centerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock(Wallet::CENTERID, $centerMoney, $time)
                ]);

                // 买家钱包资金增加
                $buyerMoney = Wallet::whereUid($indentData->buyer_id)->lockForUpdate()->value('available_money') + $buyerM;
                Wallet::whereUid($indentData->buyer_id)->update([
                    'available_money' => $buyerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock($indentData->buyer_id, $buyerMoney, $time)
                ]);

                // 生成交易流水
                $key         = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
                $runwaterNum = createRunwaterNum($key);
                Runwater::create([
                    'runwater_num' => $runwaterNum,
                    'from_uid'     => Wallet::CENTERID,
                    'to_uid'       => $indentData->buyer_id,
                    'indent_id'    => $indentData->indent_id,
                    'indent_num'   => $indentData->indent_num,
                    'type'         => Runwater::TYPE['对方取消订单退款'],
                    'direction'    => Runwater::DIRECTION['转入'],
                    'money'        => $buyerM,
                    'status'       => Runwater::STATUS['成功']
                ]);
                Cache::increment($key);

                // 修改订单信息
                $indentData->status       = IndentInfo::STATUS['交易中卖家取消订单'];
                $indentData->cancel_cause = $cancelCause;
                $indentData->save();
            } catch (\Exception $e) {
                throw new Exception('操作失败');
            }
        });

        return true;
    }

    // 卖家完成
    public static function sellerComplete($indentData)
    {
        $indentData->status = IndentInfo::STATUS['卖方完成'];
        $re                 = $indentData->save();
        if (!$re)
            throw new Exception('操作失败');

        return true;
    }

    // 成果文档
    public static function addAchievementsFile($indentData, $achievements_file)
    {
        $indentData->achievements_file = $achievements_file;
        $re                            = $indentData->save();
        if (!$re)
            throw new Exception('操作失败');

        return true;
    }

    // 买家完成
    public static function buyerComplete($indentData)
    {
        try {
            // 修改状态
            $indentData->status = IndentInfo::STATUS['全部完成'];
            $indentData->save();

            // 存入延迟队列
            $delayTime = SystemSetting::whereSettingName('trans_payment_delay')->value('value');
            IndentSettlement::dispatch($indentData->indent_num)->delay($delayTime);
        } catch (\Exception $e) {
            throw new Exception('操作失败');
        }

        return true;
    }
}