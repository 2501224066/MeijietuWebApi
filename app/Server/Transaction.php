<?php


namespace App\Server;


use App\Jobs\IndentSettlement;
use App\Jobs\SendSms;
use App\Server\Captcha;
use App\Models\Data\IndentInfo;
use App\Models\System\Setting;
use App\Models\Pay\Runwater;
use App\Models\Pay\Wallet;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class Transaction
{
    // 交易中禁止取消主题
    const TRANS_NO_CANCEL_THEME = ['软文套餐'];

    // 交易中买家取消订单资金计算
    public static function inTransactionBuyerCancelCountMoney($indentAmount, $compensateFee)
    {
        // 用户获得赔偿比率
        $v = Setting::whereSettingName('userbtain_compensate_ratio')->value('value');
        // 卖家分得买家赔偿费
        $sellerGetFee = floor($compensateFee * $v);
        // 卖家获得资金 (自身抵押赔偿费+ 分得买家赔偿费）
        $re['sellerUp'] = $compensateFee + $sellerGetFee;
        // 买家获得资金 (购买资金 - 赔偿保证费)
        $re['buyerUp'] = $indentAmount - $compensateFee;
        // 公共钱包退还
        $re['centerDown'] = $re['sellerUp'] + $re['buyerUp'];

        return $re;
    }

    // 交易中卖家取消订单资金计算
    public static function inTransactionSellerCancelMoney($indentAmount, $compensateFee)
    {
        // 用户获得赔偿比率
        $v = Setting::whereSettingName('userbtain_compensate_ratio')->value('value');
        // 买家家分得买家赔偿费
        $buyerGetFee = floor($compensateFee * $v);
        // 买家获得资金 (购买资金 + 分得卖家赔偿费)
        $re['buyerUp'] = $indentAmount + $buyerGetFee;
        // 公共钱包退还
        $re['centerDown'] = $re['buyerUp'];

        return $re;
    }

    // 订单结算资金计算
    public static function indentSettlementCountMoney($compensateFee, $sellerIncome)
    {
        // 卖家获得资金 (抵押赔偿保证费 + 订单数据中卖家收入)
        $re['sellerUp'] = $compensateFee + $sellerIncome;
        // 公共钱包退还
        $re['centerDown'] = $re['sellerUp'];

        return $re;
    }

    /**
     * 短信通知
     * @param mixed $indentData 订单数据
     * @param string $toUser 发送对象
     * @param string $status 状态描述
     */
    public static function sms($indentData, $toUser, $status)
    {
        switch ($toUser) {
            case 'buyer':
                $user = User::whereUid($indentData->buyer_id)->first();
                break;
            case 'seller':
                $user = User::whereUid($indentData->seller_id)->first();
                break;
        }

        SendSms::dispatch(
            Captcha::TYPE['订单通知'],
            $user->phone,
            [
                'name'       => $user->nickname,
                'indent_num' => $indentData->indent_num,
                'status'     => $status
            ])
            ->onQueue('SendSms');
    }
}