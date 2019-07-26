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
    // 交易中买家取消订单资金计算
    public static function inTransactionBuyerCancelCountMoney($indentAmount, $compensateFee)
    {
        // 用户获得赔偿比率
        $v = SystemSetting::whereSettingName('userbtain_compensate_ratio')->value('value');
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
        $v = SystemSetting::whereSettingName('userbtain_compensate_ratio')->value('value');
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
}