<?php


namespace App\Service;


use App\Models\Up\Runwater;
use App\Models\Up\Wallet;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class AliPay
{
    /**
     * 支付宝回调操作
     * @param array $data 回调数据
     * @throws \Throwable
     */
    public static function backOP($data)
    {
        Log::notice('支付宝回调参数', $data);
        $uid   = null;
        $money = null;

        DB::transaction(function () use ($data, &$uid, &$money) {
            try {
                $money = $data['total_amount'];
                // 检查流水是否存在
                $runWater = Runwater::checkHas($data['out_trade_no']);
                $uid      = $runWater->to_uid;
                // 检测是否为重复回调
                Runwater::checkMoreBack($data['trade_no']);
                // 金额比对
                if ($runWater->money != $money) throw new Exception('回调金额异常');
                // 校验修改校验锁
                Wallet::checkChangLock($uid);
                // 充值成功流水修改
                Runwater::rechargeBackSuccessUpdate(
                    $data['out_trade_no'],
                    $data['gmt_create'],
                    $data['trade_no'],
                    $data['total_amount']);
                // 用户资金增加
                Wallet::updateWallet($uid, $money, Wallet::UP_OR_DOWN['增加']);
            } catch (Exception $e) {
                throw new Exception('支付宝回调失败 ' . $e->getMessage());
            }
        });

        Log::info('用户' . User::whereUid($uid)->value('nickname') . '充值' . $money . '元');
    }
}