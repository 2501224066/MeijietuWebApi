<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Transaction as TransactionRequests;
use App\Models\Indent\IndentInfo;
use App\Models\Up\Wallet;
use App\Models\User;
use App\Service\Transaction;

class TransactionController extends BaseController
{
    /**
     * 订单付款
     * @param  TransactionRequests $request
     * @return mixed
     */
    public function indentPayment(TransactionRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['广告主']);
        // 订单数据
        $indentData = IndentInfo::whereIndentNum($request->indent_num)->first();
        // 检查订单状态
        IndentInfo::checkIndentStatus($indentData->status, IndentInfo::STATUS['待付款']);
        // 检测订单归属
        IndentInfo::checkIndentBelong($indentData->buyer_id);
        // 校验钱包状态
        Wallet::checkStatus($indentData->buyer_id, Wallet::STATUS['启用']);
        // 校验修改校验锁
        Wallet::checkChangLock($indentData->buyer_id);
        // 钱包余额是够足够
        Wallet::hasEnoughMoney($indentData->indent_amount);
        // 支付购买
        IndentInfo::pay($indentData);

        return $this->success();
    }

    /**
     * 买家添加需求文档
     * @param  TransactionRequests $request
     * @return mixed
     */
    public function addDemandFile(TransactionRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['广告主']);
        // 订单数据
        $indentData = IndentInfo::whereIndentNum($request->indent_num)->first();
        // 检查订单状态
        IndentInfo::checkIndentStatus($indentData->status, IndentInfo::STATUS['已付款待接单']);
        // 检测订单归属
        IndentInfo::checkIndentBelong($indentData->buyer_id);
        // 添加
        Transaction::addDemandFile($indentData, $request->demand_file);

        return $this->success();
    }

    /**
     * 卖家接单
     * @param  TransactionRequests $request
     * @return mixed
     */
    public function acceptIndent(TransactionRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 订单数据
        $indentData = IndentInfo::whereIndentNum($request->indent_num)->first();
        // 检查订单状态
        IndentInfo::checkIndentStatus($indentData->status, IndentInfo::STATUS['已付款待接单']);
        // 检测订单归属
        IndentInfo::checkIndentBelong($indentData->seller_id);
        // 校验钱包状态
        Wallet::checkStatus($indentData->seller_id, Wallet::STATUS['启用']);
        // 校验修改校验锁
        Wallet::checkChangLock($indentData->seller_id);
        // 钱包余额是够足够
        Wallet::hasEnoughMoney($indentData->compensate_fee);
        // 支付赔偿保证金
        IndentInfo::payCompensateFee($indentData);

        return $this->success();
    }
}