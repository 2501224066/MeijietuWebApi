<?php


namespace App\Http\Controllers\v1;


use App\Http\Requests\Pay as PayRequests;
use App\Models\Up\Runwater;
use App\Models\Up\Wallet;
use App\Models\User;
use App\Service\AliPay;
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
        $runwaterNum = Runwater::createRechargeRunwater($request->money, 'lianLianPay');
        // 组合请求连连数据
        $data = LianLianPay::lianlianRequestData($runwaterNum, htmlspecialchars($request->money));

        return $this->success([
            'link' => env('LIANLIAN_PAY_LIANLIAN_URL'),
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
        $runwaterNum = Runwater::createRechargeRunwater($request->money, 'aliPay');
        // 数据
        $order                = [
            'out_trade_no' => $runwaterNum,
            'total_amount' => $request->money,
            'subject'      => '支付宝充值',
        ];

        return Pay::alipay()->web($order)->pay();
    }

    /**
     * 支付宝充值回调
     */
    public function aliPayRechargeBack()
    {
        // 回调参数
        $alipay = Pay::alipay();
        // 回调操作
        AliPay::backOP($alipay);
        // 返回连连响应参数
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