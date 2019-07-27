<?php


namespace App\Http\Controllers\v1;

use App\Models\Nb\Goods;
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

    /**
     * 商品审核
     * @param SalesmanRequests $request
     * @return mixed
     */
    public function goodsVerify(SalesmanRequests $request)
    {
        // 身份必须为业务员
        User::checkIdentity(User::IDENTIDY['业务员']);
        // 通过审核
        if ($request->verify_status == Goods::VERIFY_STATUS['已通过'])
            Salesman::verifySuccess($request->goods_num);
        // 未通过审核
        if ($request->verify_status == Goods::VERIFY_STATUS['未通过'])
            Salesman::verifyFail($request->goods_num, htmlspecialchars($request->verify_cause));

        return $this->success();
    }

    /**
     * 订单议价
     * @param SalesmanRequests $request
     * @return mixed
     */
    public function indentBargaining(SalesmanRequests $request)
    {
        // 身份必须为业务员
        User::checkIdentity(User::IDENTIDY['业务员']);
        // 议价操作
        Salesman::bargainingOP($request->indent_num, htmlspecialchars($request->seller_income));

        return $this->success();
    }

    /**
     * 软文商品设置价格
     * @param SalesmanRequests $request
     * @return mixed
     */
    public function setSoftArticlePrice(SalesmanRequests $request)
    {
        // 身份必须为业务员
        User::checkIdentity(User::IDENTIDY['业务员']);
        // 设置软文价格操作
        Salesman::setSoftArticlePriceOP($request->goods_num, htmlspecialchars($request->price));

        return $this->success();
    }
}