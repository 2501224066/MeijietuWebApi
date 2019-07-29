<?php


namespace App\Service;


use App\Models\Up\Runwater;
use App\Models\Up\Wallet;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class AliPay
{
    /**
     * 连连回调操作
     * @param array $data 回调数据
     * @throws \Throwable
     */
    public static function backOP($data)
    {
        Log::notice('支付宝回调参数', $data);

        $sign = $data['sign'];
        unset($data['sign']);

        // 检查流水是否存在
        $runWater = Runwater::checkHas($data['no_order']);
        // 检测是否为重复回调
        Runwater::checkMoreBack($data['oid_paybill']);
        // 验参
        if (!self::RSAverify($data, $sign)) {
            Log::notice('连连回调RSA验签失败');
            throw new Exception('操作失败');
        }

        // 金额比对
        if ($runWater->money != $data['money_order']) {
            Log::notice('连连回调金额异常', ['流水金额' => $runWater->money, '回调金额' => $data['money_order']]);
            throw new Exception('操作失败');
        }

        // 校验修改校验锁
        Wallet::checkChangLock($runWater->to_uid);

        // 记录流水并修改用户资金
        Runwater::backSuccOp($data, $runWater->to_uid);

        Log::info('用户' . User::whereUid($runWater->to_uid)->value('nickname') . '充值' . $data['money_order'] . '元');
    }
}