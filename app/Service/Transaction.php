<?php


namespace App\Service;


use App\Models\Indent\IndentInfo;
use App\Models\Up\Runwater;
use App\Models\Up\Wallet;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

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
    public static function pay($indentData)
    {
        DB::transaction(function () use ($indentData) {
            try {
                $time = date('Y-m-d H:i:s');
                $M = $indentData->indent_amount;

                // 公共钱包资金增加
                $centerMoney = Wallet::whereUid(Wallet::CENTERID)->value('available_money') + $M;
                Wallet::whereUid(Wallet::CENTERID)->update([
                    'available_money' => $centerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock(Wallet::CENTERID, $centerMoney, $time)
                ]);

                // 买家钱包资金扣除
                $buyerMoney = Wallet::whereUid($indentData->buyer_id)->value('available_money') - $M;
                Wallet::whereUid($indentData->buyer_id)->update([
                    'available_money' => $buyerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock($indentData->buyer_id,$buyerMoney, $time)
                ]);

                // 生成交易流水
                $key         = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
                $runwaterNum = createRunwaterNum($key);
                Runwater::create([
                    'runwater_num' => $runwaterNum,
                    'form_uid'     => $indentData->buyer_id,
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
                $M = $indentData->compensate_fee;

                // 公共钱包资金增加
                $centerMoney = Wallet::whereUid(Wallet::CENTERID)->value('available_money') + $M;
                Wallet::whereUid(Wallet::CENTERID)->update([
                    'available_money' => $centerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock(Wallet::CENTERID, $centerMoney, $time)
                ]);

                // 卖家钱包资金扣除
                $sellerMoney = Wallet::whereUid($indentData->seller_id)->value('available_money') - $M;
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
                    'form_uid'     => $indentData->seller_id,
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
                $indentData->status     = IndentInfo::STATUS['执行中'];
                $indentData->save();
            } catch (\Exception $e) {
                throw new Exception('操作失败');
            }
        });

        return true;
    }
}