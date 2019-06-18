<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Goods as GoodsRequests;
use App\Models\Nb\Collection;
use App\Models\User;

class CollectionController extends BaseController
{

    /**
     * 收藏商品
     * @param GoodsRequests $request
     * @return mixed
     */
    public function collectionGoods(GoodsRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['广告主']);
        // 转换数据类型
        $goodsIdArr = json_decode($request->goods_id_json);
        // 添加收藏
        Collection::add($goodsIdArr);

        return $this->success();
    }

    /**
     * 获取收藏商品
     * @return Collection[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getCollection()
    {
        return Collection::getCollection();
    }

    /**
     * 删除收藏商品
     * @return Collection[]|\Illuminate\Database\Eloquent\Collection
     */
    public function delCollection(GoodsRequests $request)
    {
        // 转换数据类型
        $goodsIdArr = json_decode($request->goods_id_json);
        // 删除
        Collection::del($goodsIdArr);

        return $this->success();
    }
}