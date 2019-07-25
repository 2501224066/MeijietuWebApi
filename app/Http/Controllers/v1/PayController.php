<?php


namespace App\Http\Controllers\v1;


use App\Http\Requests\Pay as PayRequests;
use App\Models\Up\Runwater;
use App\Models\Up\Wallet;
use App\Models\User;
use App\Service\Pay;
use App\Service\Pub;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class PayController extends BaseController
{
    /**
     * 充值
     * @param PayRequests $request
     * @return mixed
     */
    public function recharge(PayRequests $request)
    {
        $uid = JWTAuth::user()->uid;
        // 校验钱包状态
        Wallet::checkStatus($uid, Wallet::STATUS['启用']);
        // 生成充值流水
        $runwaterNum = Runwater::createRechargeRunwater($request->money);
        // 组合请求连连数据
        $data = Pay::lianlianRequestData($runwaterNum, htmlspecialchars($request->money));

        return $this->success([
            'link' => env('PAY_LIANLIAN_URL'),
            'post' => $data
        ]);
    }

    /**
     * 充值回调
     * @return false|string
     */
    public function lianLianPayRechargeBack()
    {
        // 接收数据
        $data = file_get_contents("php://input");
        $data = json_decode($data, TRUE);

        // 回调操作
        Pay::backOP($data);

        // 返回连连响应参数
        return json_encode(["ret_code" => "0000", "ret_msg" => "交易成功"]);
    }

    /**
     * 提现
     * @param PayRequests $request
     * @return mixed
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