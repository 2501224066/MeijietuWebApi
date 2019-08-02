<?php


namespace App\Http\Controllers\v1;

use App\Http\Requests\Transaction as TransactionRequests;
use App\Jobs\IndentSettlement;
use App\Jobs\SendSms;
use App\Server\Captcha;
use App\Models\Data\IndentInfo;
use App\Models\Data\IndentItem;
use App\Models\System\Setting;
use App\Models\Pay\Runwater;
use App\Models\Pay\Wallet;
use App\Models\User;
use App\Server\Transaction;
use App\Server\Pub;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransactionController extends BaseController
{
    /**
     * 待付款删除订单
     * @param TransactionRequests $request
     * @return mixed
     */
    public function deleteIndentBeforePayment(TransactionRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['广告主']);
        // 订单数据
        $indentData = IndentInfo::whereIndentNum($request->indent_num)->first();
        // 检查订单状态
        Pub::checkParm($indentData->status, IndentInfo::STATUS['待付款'], '订单状态错误');
        // 检测订单归属
        IndentInfo::checkIndentBelong([$indentData->buyer_id]);
        // 删除
        $indentData->delete_status = IndentInfo::DELETE_STATUS['已删除'];
        $indentData->save();

        // 发送短信
        Transaction::sms($indentData->indent_num, $indentData->seller_id, '买家取消此订单');
        Log::info('订单' . $request->indent_num . '被删除');
        return $this->success();
    }

    /**
     * 订单付款
     * @param TransactionRequests $request
     * @return mixed
     * @throws \Throwable
     */
    public function indentPayment(TransactionRequests $request)
    {
        $indentData = null;
        DB::transaction(function () use ($request, &$indentData) {
            try {
                // 检查身份
                User::checkIdentity(User::IDENTIDY['广告主']);
                // 订单数据 *加锁
                $indentData = IndentInfo::whereIndentNum($request->indent_num)->lockForUpdate()->first();
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
                // 公共钱包资金增加
                Wallet::updateWallet(Wallet::CENTERID, $indentData->indent_amount, Wallet::UP_OR_DOWN['增加']);
                // 买家钱包资金减少
                Wallet::updateWallet($indentData->buyer_id, $indentData->indent_amount, Wallet::UP_OR_DOWN['减少']);
                // 生成交易流水
                Runwater::createTransRunwater($indentData->buyer_id,
                    Wallet::CENTERID,
                    Runwater::TYPE['订单付款'],
                    Runwater::DIRECTION['转出'],
                    $indentData->indent_amount,
                    $indentData->indent_id);
                // 修改订单信息
                IndentInfo::updateIndent($indentData, IndentInfo::STATUS['已付款待接单'], $indentData->indent_amount);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        });

        // 发送短信
        Transaction::sms($indentData->indent_num, $indentData->seller_id, '买家已付款');
        Log::info('订单' . $request->indent_num . '付款完成');
        return $this->success();
    }

    /**
     * 买家添加需求文档
     * @param TransactionRequests $request
     * @return mixed
     */
    public function addDemandFile(TransactionRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['广告主']);
        // 订单数据
        $indentData = IndentInfo::whereIndentNum($request->indent_num)->first();
        // 仅上传一次限制
        Pub::checkParm($indentData->demand_file, true, '只可上传一次');
        // 检查订单状态
        Pub::checkParm($indentData->status, IndentInfo::STATUS['已付款待接单'], '订单状态错误');
        // 检测订单归属
        IndentInfo::checkIndentBelong([$indentData->buyer_id]);
        // 添加
        $indentData->demand_file = $request->demand_file;
        if (!$indentData->save()) throw new Exception('操作失败');

        // 发送短信
        Transaction::sms($indentData->indent_num, $indentData->seller_id, '买家已添加需求文档');
        Log::info('订单' . $request->indent_num . '添加需求文档完成');
        return $this->success();
    }

    /**
     * 待接单买家取消订单/卖家拒单
     *  全额退款给买家
     * @param TransactionRequests $request
     * @return mixed
     * @throws \Throwable
     */
    public function acceptIndentBeforeCancel(TransactionRequests $request)
    {
        $indentData = null;
        DB::transaction(function () use ($request, &$indentData) {
            try {
                // 订单数据 *加锁
                $indentData = IndentInfo::whereIndentNum($request->indent_num)->lockForUpdate()->first();
                // 检查订单状态
                Pub::checkParm($indentData->status, IndentInfo::STATUS['已付款待接单'], '订单状态错误');
                // 检测订单归属
                IndentInfo::checkIndentBelong([$indentData->buyer_id, $indentData->seller_id]);
                // 校验钱包状态
                Wallet::checkStatus($indentData->buyer_id, Wallet::STATUS['启用']);
                // 校验修改校验锁
                Wallet::checkChangLock($indentData->buyer_id);
                // 公共钱包资金减少
                Wallet::updateWallet(Wallet::CENTERID, $indentData->indent_amount, Wallet::UP_OR_DOWN['减少']);
                // 买家家钱包资金增加
                Wallet::updateWallet($indentData->buyer_id, $indentData->indent_amount, Wallet::UP_OR_DOWN['增加']);
                // 生成交易流水
                Runwater::createTransRunwater(Wallet::CENTERID,
                    $indentData->buyer_id,
                    Runwater::TYPE['取消订单全额退款'],
                    Runwater::DIRECTION['转入'],
                    $indentData->indent_amount,
                    $indentData->indent_id);
                // 修改订单信息
                $status = (JWTAuth::user()->uid == $indentData->buyer_id) ? IndentInfo::STATUS['待接单买家取消订单'] : IndentInfo::STATUS['卖家拒单'];
                IndentInfo::updateIndent($indentData, $status, null, htmlspecialchars($request->cancel_cause));
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        });

        // 发送短信
        Transaction::sms($indentData->indent_num, $indentData->buyer_id, '待接单时订单被取消');
        Transaction::sms($indentData->indent_num, $indentData->seller_id, '待接单时订单被取消');
        Log::info('订单' . $request->indent_num . '待接单买家取消订单/卖家拒单');
        return $this->success();
    }

    /**
     * 卖家接单
     *  支付赔偿保证金
     * @param TransactionRequests $request
     * @return mixed
     * @throws \Throwable
     */
    public function acceptIndent(TransactionRequests $request)
    {
        $indentData = null;
        DB::transaction(function () use ($request, &$indentData) {
            try {
                // 检查身份
                User::checkIdentity(User::IDENTIDY['媒体主']);
                // 订单数据 *加锁
                $indentData = IndentInfo::whereIndentNum($request->indent_num)->lockForUpdate()->first();
                // 检查订单状态
                Pub::checkParm($indentData->status, IndentInfo::STATUS['已付款待接单'], '订单状态错误');
                // 检查议价状态
                IndentInfo::checkSaceBuyerIncomeStatus($indentData->bargaining_status, IndentInfo::BARGAINING_STATUS['已完成']);
                // 检测订单归属
                IndentInfo::checkIndentBelong([$indentData->seller_id]);
                // 校验钱包状态
                Wallet::checkStatus($indentData->seller_id, Wallet::STATUS['启用']);
                // 校验修改校验锁
                Wallet::checkChangLock($indentData->seller_id);
                // 钱包余额是够足够
                Wallet::hasEnoughMoney($indentData->compensate_fee);
                if ($indentData->compensate_fee > 0) {
                    // 公共钱包资金增加
                    Wallet::updateWallet(Wallet::CENTERID, $indentData->compensate_fee, Wallet::UP_OR_DOWN['增加']);
                    // 卖家钱包资金减少
                    Wallet::updateWallet($indentData->buyer_id, $indentData->compensate_fee, Wallet::UP_OR_DOWN['减少']);
                    // 生成交易流水
                    Runwater::createTransRunwater($indentData->buyer_id,
                        Wallet::CENTERID,
                        Runwater::TYPE['支付赔偿保证费'],
                        Runwater::DIRECTION['转出'],
                        $indentData->compensate_fee,
                        $indentData->indent_id);
                }
                // 修改订单信息
                IndentInfo::updateIndent($indentData, IndentInfo::STATUS['交易中']);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        });

        Transaction::sms($indentData->indent_num, $indentData->buyer_id, '卖家接单');
        Log::info('订单' . $request->indent_num . '卖家接单');
        return $this->success();
    }

    /**
     * 交易中买家取消订单
     *  1.扣除赔偿保证费退给买家
     *    将卖家自己的赔偿保证费与分成的买家赔偿退给卖家
     *
     *  2.软文套餐禁止取消
     * @param TransactionRequests $request
     * @return mixed
     * @throws \Throwable
     */
    public function inTransactionBuyerCancel(TransactionRequests $request)
    {
        $indentData = null;
        DB::transaction(function () use ($request, &$indentData) {
            try {
                // 检查身份
                User::checkIdentity(User::IDENTIDY['广告主']);
                // 订单数据 *加锁
                $indentData = IndentInfo::whereIndentNum($request->indent_num)->lockForUpdate()->first();
                // 部分主题禁止取消
                $themeName = IndentItem::where($indentData->indent_id)->value('theme_name');
                if (in_array($themeName, Transaction::TRANS_NO_CANCEL_THEME))
                    throw new Exception('此主题禁止取消');
                // 检查订单状态
                Pub::checkParm($indentData->status, IndentInfo::STATUS['交易中'], '订单状态错误');
                // 检测订单归属
                IndentInfo::checkIndentBelong([$indentData->buyer_id]);
                // 校验卖家钱包状态
                Wallet::checkStatus($indentData->seller_id, Wallet::STATUS['启用']);
                // 校验卖家修改校验锁
                Wallet::checkChangLock($indentData->seller_id);
                // 校验买家钱包状态
                Wallet::checkStatus($indentData->buyer_id, Wallet::STATUS['启用']);
                // 校验买家修改校验锁
                Wallet::checkChangLock($indentData->buyer_id);
                // 交易中买家取消订单资金计算
                $countMoney = Transaction::inTransactionBuyerCancelCountMoney($indentData->indent_amount, $indentData->compensate_fee);
                // 公共钱包资金减少
                Wallet::updateWallet(Wallet::CENTERID, $countMoney['centerDown'], Wallet::UP_OR_DOWN['减少']);
                // 买家钱包资金增加
                Wallet::updateWallet($indentData->buyer_id, $countMoney['buyerUp'], Wallet::UP_OR_DOWN['增加']);
                // 生成交易流水
                Runwater::createTransRunwater(Wallet::CENTERID,
                    $indentData->buyer_id,
                    Runwater::TYPE['取消订单非全额退款'],
                    Runwater::DIRECTION['转入'],
                    $countMoney['buyerUp'],
                    $indentData->indent_id);
                // 卖家钱包资金增加
                Wallet::updateWallet($indentData->seller_id, $countMoney['sellerUp'], Wallet::UP_OR_DOWN['增加']);
                // 生成交易流水
                Runwater::createTransRunwater(Wallet::CENTERID,
                    $indentData->seller_id,
                    Runwater::TYPE['对方取消订单退款'],
                    Runwater::DIRECTION['转入'],
                    $countMoney['sellerUp'],
                    $indentData->indent_id);
                // 修改订单信息
                IndentInfo::updateIndent($indentData,
                    IndentInfo::STATUS['交易中买家取消订单'],
                    null,
                    htmlspecialchars($request->cancel_cause));
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        });

        Transaction::sms($indentData->indent_num, $indentData->seller_id, '交易中买家取消订单');
        Log::info('订单' . $request->indent_num . '交易中买家取消订单');
        return $this->success();
    }

    /**
     * 交易中卖家取消订单
     * 将购买资金与分成的卖家赔偿退给买家
     * @param TransactionRequests $request
     * @return mixed
     * @throws \Throwable
     */
    public function inTransactionSellerCancel(TransactionRequests $request)
    {
        $indentData = null;
        DB::transaction(function () use ($request, &$indentData) {
            try {
                // 检查身份
                User::checkIdentity(User::IDENTIDY['媒体主']);
                // 订单数据 *加锁
                $indentData = IndentInfo::whereIndentNum($request->indent_num)->lockForUpdate()->first();
                // 检查订单状态
                Pub::checkParm($indentData->status, IndentInfo::STATUS['交易中'], '订单状态错误');
                // 检测订单归属
                IndentInfo::checkIndentBelong([$indentData->seller_id]);
                // 校验买家钱包状态
                Wallet::checkStatus($indentData->buyer_id, Wallet::STATUS['启用']);
                // 校验买家修改校验锁
                Wallet::checkChangLock($indentData->buyer_id);
                // 交易中卖家取消订单资金计算
                $countMoney = Transaction::inTransactionSellerCancelMoney($indentData->indent_amount, $indentData->compensate_fee);
                // 公共钱包资金减少
                Wallet::updateWallet(Wallet::CENTERID, $countMoney['centerDown'], Wallet::UP_OR_DOWN['减少']);
                // 买家钱包资金增加
                Wallet::updateWallet($indentData->buyer_id, $countMoney['buyerUp'], Wallet::UP_OR_DOWN['增加']);
                // 生成交易流水
                Runwater::createTransRunwater(Wallet::CENTERID,
                    $indentData->buyer_id,
                    Runwater::TYPE['对方取消订单退款'],
                    Runwater::DIRECTION['转入'],
                    $countMoney['buyerUp'],
                    $indentData->indent_id);
                // 修改订单信息
                IndentInfo::updateIndent($indentData,
                    IndentInfo::STATUS['交易中卖家取消订单'],
                    null,
                    htmlspecialchars($request->cancel_cause));
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        });

        Transaction::sms($indentData->indent_num, $indentData->buyer_id, '交易中卖家取消订单');
        Log::info('订单' . $request->indent_num . '交易中卖家取消订单');
        return $this->success();
    }


    /**
     * 卖家确认完成
     * @param TransactionRequests $request
     * @return mixed
     */
    public function sellerConfirmComplete(TransactionRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 订单数据
        $indentData = IndentInfo::whereIndentNum($request->indent_num)->first();
        // 检查订单状态
        Pub::checkParm($indentData->status, IndentInfo::STATUS['交易中'], '订单状态错误');
        // 检测订单归属
        IndentInfo::checkIndentBelong([$indentData->seller_id]);
        // 修改订单信息
        IndentInfo::updateIndent($indentData, IndentInfo::STATUS['卖方完成']);

        Transaction::sms($indentData->indent_num, $indentData->buyer_id, '卖家确认完成');
        Log::info('订单' . $request->indent_num . '卖家确认完成');
        return $this->success();
    }

    /**
     * 卖家添加成果文档
     * @param TransactionRequests $request
     * @return mixed
     */
    public function addAchievementsFile(TransactionRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 订单数据
        $indentData = IndentInfo::whereIndentNum($request->indent_num)->first();
        // 仅上传一次限制
        Pub::checkParm($indentData->achievements_file, true, '只可上传一次');
        // 检查订单状态
        Pub::checkParm($indentData->status, IndentInfo::STATUS['卖方完成'], '订单状态错误');
        // 检测订单归属
        IndentInfo::checkIndentBelong([$indentData->seller_id]);
        // 添加
        $indentData->achievements_file = $request->demand_file;
        if (!$indentData->save()) throw new Exception('操作失败');

        Transaction::sms($indentData->indent_num, $indentData->buyer_id, '卖家添加成果文档');
        Log::info('订单' . $request->indent_num . '卖家添加成果文档');
        return $this->success();
    }

    /**
     * 买家确认完成
     * 使用延迟队列延迟打款
     * @param TransactionRequests $request
     * @return mixed
     * @throws \Throwable
     */
    public function buyerConfirmComplete(TransactionRequests $request)
    {
        $indentData = null;
        DB::transaction(function () use ($request, &$indentData) {
            try {
                // 检查身份
                User::checkIdentity(User::IDENTIDY['广告主']);
                // 订单数据
                $indentData = IndentInfo::whereIndentNum($request->indent_num)->lockForUpdate()->first();
                // 检查订单状态
                Pub::checkParm($indentData->status, IndentInfo::STATUS['卖方完成'], '订单状态错误');
                // 检测订单归属
                IndentInfo::checkIndentBelong([$indentData->buyer_id]);
                // 修改订单信息
                IndentInfo::updateIndent($indentData, IndentInfo::STATUS['全部完成']);
                // 存入延迟队列结算
                $delayTime = Setting::whereSettingName('trans_payment_delay')->value('value');
                IndentSettlement::dispatch($indentData->indent_num)->onQueue('IndentSettlement')->delay($delayTime);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        });

        Transaction::sms($indentData->indent_num, $indentData->seller_id, '买家确认完成，等待结算');
        Log::info('订单' . $request->indent_num . '买家确认完成');
        return $this->success();
    }
}