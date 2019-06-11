<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\UserShopcart as UserShopcartRequests;
use App\Models\User;
use App\Models\UserShopcart;
use App\Service\ModularData;

class UserShopcartController extends BaseController
{
    /**
     * 加入购物车
     * @param UserShopcartRequests $request
     * @return mixed
     */
    public function joinShopcart(UserShopcartRequests $request)
    {
        // 判断用户身份
        User::checkIdentity();
        // 检查模块类型
        ModularData::checkModularType($request->modular_type);
        // 判断商品是否已加入购物车
        UserShopcart::checkShopcartHas($request);
        // 检查商品价格信息
        UserShopcart::checkGoodsPrice($request);
        // 判断商品是否存在
        ModularData::checkGoodsHas($request->modular_type, $request->goods_id);
        // 加入
        UserShopcart::add($request);

        return $this->success();
    }

    /**
     * 从购物车删除
     * @param UserShopcartRequests $request
     * @return mixed
     */
    public function shopcartDel($idStr)
    {
        $idArr = explode('-', trim($idStr));
        UserShopcart::del($idArr);

        return $this->success();
    }

    /**
     * 购物车数据
     * @return mixed
     */
    public function getShopcart()
    {
        // 购物车所有商品
        $goods = UserShopcart::get();
        // 补充信息
        $re = UserShopcart::withInfo($goods);

        return $this->success($re);
    }

    /**
     * 修改价格种类
     */
    public function shopcartChangePriceclassify(UserShopcartRequests $request)
    {
        // 检查商品价格信息
        UserShopcart::checkGoodsPrice($request);
        // 修改
        UserShopcart::change($request);

        return $this->success();
    }

}