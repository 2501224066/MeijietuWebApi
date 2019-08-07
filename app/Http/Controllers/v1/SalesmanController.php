<?php


namespace App\Http\Controllers\v1;

use App\Models\Data\Demand;
use App\Models\Data\Goods;
use App\Models\User;
use App\Server\Pub;
use App\Server\Salesman;
use Mockery\Exception;
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

    /**
     * 需求失效
     * @param SalesmanRequests $request
     */
    public function invalidDemand(SalesmanRequests $request)
    {
        // 身份必须为业务员
        User::checkIdentity(User::IDENTIDY['业务员']);
        // 需求数据
        $demand = Demand::whereDemandNum($request->demand_num)->first();
        // 状态需为等待
        Pub::checkParm($demand->status, Demand::STATUS['等待'], '需求状态错误');
        // 修改
        $demand->status = Demand::STATUS['失效'];
        if (!$demand->save()) throw new Exception('操作失败');

        return $this->success();
    }
}