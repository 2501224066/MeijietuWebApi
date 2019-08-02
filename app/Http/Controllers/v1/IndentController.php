<?php


namespace App\Http\Controllers\v1;


use App\Http\Requests\Indent as IndentRequests;
use App\Http\Requests\Indent;
use App\Jobs\IndentCreatedOP;
use App\Models\Data\IndentInfo;
use App\Models\User;
use App\Server\Salesman;
use Tymon\JWTAuth\Facades\JWTAuth;

class IndentController extends BaseController
{
    /**
     * 创建订单
     * @param Indent $request
     * @return mixed
     * @throws \Throwable
     */
    public function createIndent(IndentRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['广告主']);
        // 必须未拥有客服
        Salesman::checkUserHasSalesman(JWTAuth::user()->uid,'y');
        // json转对象
        $input = json_decode($request->info, true);
        // 验证数据并创建订单
        IndentInfo::dataSorting($input);
        // 删除购物车中对应商品
        IndentCreatedOP::dispatch($input)->onQueue('IndentCreatedOP');

        return $this->success();
    }

    /**
     * 获取自己订单
     * @return mixed
     */
    public function indentBelongSelf()
    {
        // 订单数据
        $re = IndentInfo::getSelfIndent();

        return $this->success($re);
    }
}