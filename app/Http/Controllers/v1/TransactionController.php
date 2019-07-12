<?php


namespace App\Http\Controllers\v1;


use App\Http\Requests\Transaction as TransactionRequests;
use App\Models\Indent\IndentInfo;
use App\Models\Up\Wallet;
use App\Models\User;
use App\Service\Transaction;
use App\Service\Pub;
use Illuminate\Validation\Rules\In;

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
        IndentInfo::del($indentData);

        return $this->success();
    }

    /**
     * 订单付款
     * 支付订单价格
     * @param TransactionRequests $request
     * @return mixed
     */
    public function indentPayment(TransactionRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['广告主']);
        // 支付购买
        Transaction::pay($request->indent_num);

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
        // 文档是否存在;
        $count = $indentData->demand_file ? 1 : 0;
        // 仅上传一次限制
        Pub::checkParm($count, 0, '只可上传一次');
        // 检查订单状态
        Pub::checkParm($indentData->status, IndentInfo::STATUS['已付款待接单'], '订单状态错误');
        // 检测订单归属
        IndentInfo::checkIndentBelong([$indentData->buyer_id]);
        // 添加
        Transaction::addDemandFile($indentData, $request->demand_file);

        return $this->success();
    }

    /**
     * 待接单买家取消订单/卖家拒单
     * 全额退款给买家
     * @param TransactionRequests $request
     * @return mixed
     */
    public function acceptIndentBeforeCancel(TransactionRequests $request)
    {
        // 待接单取消订单/卖家拒单资金操作，录入取消原因
        Transaction::fullRefundToBuyer($request->indent_num, htmlspecialchars($request->cancel_cause));

        return $this->success();
    }

    /**
     * 卖家接单
     * 支付赔偿保证金
     * @param TransactionRequests $request
     * @return mixed
     */
    public function acceptIndent(TransactionRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 支付赔偿保证金
        Transaction::payCompensateFee($request->indent_num);

        return $this->success();
    }

    /**
     * 交易中买家取消订单
     * 扣除赔偿保证费退给买家
     * 将卖家自己的赔偿保证费与分成的买家赔偿退给卖家
     */
    public function inTransactionBuyerCancel(TransactionRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['广告主']);
        // 交易中买家取消订单资金操作
        Transaction::inTransactionBuyerCancelMoneyOP($request->indent_num, htmlspecialchars($request->cancel_cause));

        return $this->success();
    }

    /**
     * 交易中卖家取消订单
     * 将购买资金与分成的卖家赔偿退给买家
     * @param TransactionRequests $request
     * @return mixed
     */
    public function inTransactionSellerCancel(TransactionRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 交易中买家取消订单资金操作
        Transaction::inTransactionSellerCancelMoneyOP($request->indent_num, htmlspecialchars($request->cancel_cause));

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
        // 修改状态
        Transaction::sellerComplete($indentData);

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
        // 文档是否存在;
        $count = $indentData->achievements_file ? 1 : 0;
        // 仅上传一次限制
        Pub::checkParm($count, 0, '只可上传一次');
        // 检查订单状态
        Pub::checkParm($indentData->status, IndentInfo::STATUS['卖方完成'], '订单状态错误');
        // 检测订单归属
        IndentInfo::checkIndentBelong([$indentData->seller_id]);
        // 添加
        Transaction::addAchievementsFile($indentData, $request->achievements_file);

        return $this->success();
    }

    /**
     * 买方确认完成
     * 使用延迟队列延迟打款
     */
    public function buyerConfirmComplete(TransactionRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['广告主']);
        // 订单数据
        $indentData = IndentInfo::whereIndentNum($request->indent_num)->first();
        // 检查订单状态
        Pub::checkParm($indentData->status, IndentInfo::STATUS['卖方完成'], '订单状态错误');
        // 检测订单归属
        IndentInfo::checkIndentBelong([$indentData->buyer_id]);
        // 修改状态
        Transaction::buyerComplete($indentData);

        return $this->success();
    }

}