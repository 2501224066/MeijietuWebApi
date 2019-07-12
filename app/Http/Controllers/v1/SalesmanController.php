<?php


namespace App\Http\Controllers\v1;


use App\Models\User;
use App\Service\Salesman;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\Salesman as SalesmanRequests;

class SalesmanController extends BaseController
{
    /**
     * 服务用户列表
     * @return mixed
     */
    public function serveUserList()
    {
        // 身份必须为业务员
        User::checkIdentity(User::IDENTIDY['业务员']);
        // 服务用户
        $re = User::whereSalesmanId(JWTAuth::user()->uid)
            ->get()
            ->each(function ($item) {
                unset($item['email'], $item['phone'], $item['password'], $item['ip'], $item['salesman_id'], $item['salesman_name'], $item['created_at'], $item['updated_at']);
            });

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
}