<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Goods as GoodsRequests;
use App\Models\Nb\Shopcart;
use App\Models\User;

class ShopcartController extends BaseController
{

    /**
     * 加入购物车
     * @param GoodsRequests $request
     * @return mixed
     */
    public function joinShopcart(GoodsRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['广告主']);
        // 转换数据类型
        $goodsIdArr = json_decode($request->goods_id_json);
        // 添加收藏
        Shopcart::join($goodsIdArr);

        return $this->success();
    }

    /**
     * 获取购物车商品
     * @return Shopcart[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getShopcart()
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['广告主']);

        return Shopcart::getShopcart();
    }

    /**
     * 删除购物车商品
     * @param GoodsRequests $request
     * @return mixed
     */
    public function delShopcart(GoodsRequests $request)
    {
        // 转换数据类型
        $goodsIdArr = json_decode($request->goods_id_json);
        // 删除
        Shopcart::del($goodsIdArr);

        return $this->success();
    }
}