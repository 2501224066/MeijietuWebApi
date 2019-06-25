<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Indent as IndentRequests;
use App\Models\Indent\IndentInfo;
use App\Models\Up\Wallet;

class IndentController extends BaseController
{
    /**
     * 创建订单
     * @param IndentRequests $request
     * @return mixed
     */
    public function createIndent(IndentRequests $request)
    {
        // json转对象
        $info = json_decode($request->info, true);
        // 数据整理
        $data = IndentInfo::dataSorting($info);
        // 添加
        IndentInfo::add($data);

        return $this->success();
    }

    /**
     * 订单付款
     * @param IndentRequests $request
     * @return mixed
     */
    public function indentPayment(IndentRequests $request)
    {
        // 订单数据
        $indentData = IndentInfo::whereIndentNum($request->indent_num)->first();
        // 检查订单状态
        IndentInfo::checkIndentStatus($indentData->status, IndentInfo::STATUS['待付款']);
        // 检测订单归属
        IndentInfo::checkIndentBelong($indentData->buyer_id);
        // 钱包余额是够足够购买
        Wallet::hasEnoughMoney($indentData->indent_amount);
        // 支付购买
        IndentInfo::pay($indentData);

        return $this->success();
    }
}