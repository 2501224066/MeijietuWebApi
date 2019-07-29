<?php


namespace App\Http\Controllers\v1;


use App\Http\Requests\Pay as PayRequests;
use App\Models\Up\Runwater;
use App\Models\Up\Wallet;
use App\Models\User;
use App\Service\LianLianPay;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Yansongda\LaravelPay\Facades\Pay;

class PayController extends BaseController
{
    /**
     * 连连充值
     * @param PayRequests $request
     * @return mixed
     */
    public function LianLianPayRecharge(PayRequests $request)
    {
        $uid = JWTAuth::user()->uid;
        // 校验钱包状态
        Wallet::checkStatus($uid, Wallet::STATUS['启用']);
        // 生成充值流水
        $runwaterNum = Runwater::createRechargeRunwater($request->money);
        // 组合请求连连数据
        $data = LianLianPay::lianlianRequestData($runwaterNum, htmlspecialchars($request->money));

        return $this->success([
            'link' => env('PAY_LIANLIAN_URL'),
            'post' => $data
        ]);
    }

    /**
     * 连连充值回调
     * @return false|string
     * @throws \Throwable
     */
    public function LianLianPayRechargeBack()
    {
        // 接收数据
        $data = file_get_contents("php://input");
        $data = json_decode($data, TRUE);
        // 回调操作
        LianLianPay::backOP($data);
        // 返回连连响应参数
        return json_encode(["ret_code" => "0000", "ret_msg" => "交易成功"]);
    }

    /**
     * 支付宝充值
     * @param PayRequests $request
     * @return mixed
     */
    public static function aliPayRecharge(PayRequests $request)
    {
        $uid = JWTAuth::user()->uid;
        // 校验钱包状态
        Wallet::checkStatus($uid, Wallet::STATUS['启用']);
        // 生成充值流水
        $runwaterNum = Runwater::createRechargeRunwater($request->money);
        // 数据
        $order = [
            'out_trade_no' => $runwaterNum,
            'total_amount' => $request->money,
            'subject'      => '支付宝充值',
        ];

        return Pay::alipay()->web($order);
    }

    /**
     * 支付宝充值回调
     */
    public function aliPayRechargeBack()
    {
        $alipay = Pay::alipay();
        $uid    = JWTAuth::user()->uid;
        try {
            // 验参
            $data = $alipay->verify(); // 是的，验签就这么简单！
            // 检查流水是否存在
            $runWater = Runwater::checkHas($data['out_trade_no']);
            // 检测是否为重复回调
            Runwater::checkMoreBack($data['trade_no']);
            // 金额比对
            if ($runWater->money != $data['total_amount']) {
                Log::notice('支付宝回调金额异常', ['流水金额' => $runWater->money, '回调金额' => $data['total_amount']]);
                throw new Exception('操作失败');
            }
            // 校验修改校验锁
            Wallet::checkChangLock($runWater->to_uid);
            // 充值成功流水修改
            Runwater::rechargeBackSuccessUpdate(
                $data['out_trade_no'],
                $data['trade_no'],
                $data['total_amount'],
                'aliPay');
            // 用户资金增加
            Wallet::updateWallet($uid, $data['money_order'], Wallet::UP_OR_DOWN['增加']);
        } catch (\Exception $e) {
            Log::error('操作失败');
        }

        return $alipay->success();
    }

    /**
     * 提现
     * @param PayRequests $request
     * @return mixed
     * @throws \Throwable
     */
    public function extract(PayRequests $request)
    {
        $user = JWTAuth::user();
        // 检测实名认证
        User::checkRealnameStatus($user->realname_status, 'n');
        // 提现操作
        Runwater::extractOP($user->uid, $request->money);

        return $this->success();
    }
}