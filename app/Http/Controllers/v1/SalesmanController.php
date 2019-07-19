<?php


namespace App\Http\Controllers\v1;


use App\Models\User;
use App\Service\Salesman;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\Salesman as SalesmanRequests;

class SalesmanController extends BaseController
{
    /**
     * 服务用户搜索
     * @return mixed
     */
    public function serveUserSelect(SalesmanRequests $request)
    {
        // 身份必须为业务员
        User::checkIdentity(User::IDENTIDY['业务员']);
        // 服务用户
        $re = Salesman::serveUser($request);
        return $this->success($re);
    }

    /**
     * 服务商品搜索
     * @param SalesmanRequests $request
     * @return mixed
     */
    public function serveGoodsSelect(SalesmanRequests $request)
    {
        // 身份必须为业务员
        User::checkIdentity(User::IDENTIDY['业务员']);
        // 服务客户ARR
        $userArr = User::whereSalesmanId(JWTAuth::user()->uid)->pluck('uid');
        // 搜索
        $re = Salesman::serveGoods($request, $userArr);

        return $this->success($re);
    }

    /**
     * 服务订单搜索
     * @param SalesmanRequests $request
     * @return mixed
     */
    public function serveIndentSelect(SalesmanRequests $request)
    {
        // 身份必须为业务员
        User::checkIdentity(User::IDENTIDY['业务员']);
        // 搜索
        $re = Salesman::serveIndent($request);

        return $this->success($re);
    }

    /*
     * 商品通过审核
     */
}