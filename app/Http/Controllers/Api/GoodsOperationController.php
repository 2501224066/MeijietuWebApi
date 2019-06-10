<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\GoodsOperation as GoodsOperationRequests;
use App\Models\UserCollection;

class GoodsOperationController extends BaseController
{
    /**
     * 收藏商品
     * @param GoodsOperationRequests $request
     * @return mixed
     */
    public function collectionGoods(GoodsOperationRequests $request)
    {
        // 判断用户身份
        UserCollection::checkIdentity();
        // 检查模块类型
        UserCollection::checkModularType($request->modular_type);
        // 判断商品是否已经收藏
        UserCollection::checkCollectionHas($request);
        // 判断商品是否存在
        UserCollection::checkGoodsHas($request);
        // 添加收藏
        UserCollection::add($request);

        return $this->success();
    }

    /**
     * 删除收藏
     * @param GoodsOperationRequests $request
     * @return mixed
     */
    public function delCollection(GoodsOperationRequests $request)
    {
        // 检查模块类型
        UserCollection::checkModularType($request->modular_type);
        // 删除收藏
        UserCollection::del($request);

        return $this->success();
    }

}