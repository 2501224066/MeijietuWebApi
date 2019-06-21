<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Pay as PayRequests;
use App\Models\Up\Runwater;
use App\Service\Pay;

class PayController extends BaseController
{
    /**
     * 充值
     * @param PayRequests $request
     * @return mixed
     */
    public function recharge(PayRequests $request)
    {
        // 生成流水单
        $runwaterNum = Runwater::createRunwater($request->money);
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
    public static function lianLianPayRechargeBack()
    {
        // 接收数据
        $data = file_get_contents( "php://input");
        $data = json_decode($data);

        // 回调操作
        Pay::back($data);

        // 返回连连响应参数
        return  json_encode(["ret_code"=>"0000", "ret_msg"=>"交易成功" ]);
}
}