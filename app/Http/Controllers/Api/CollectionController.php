<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\Collection as CollectionRequests;
use App\Models\UserCollection;

class CollectionController extends BaseController
{
    /**
     * 收藏商品
     * @param CollectionRequests $request
     * @return mixed
     */
    public function collectionGoods(CollectionRequests $request)
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
     * @param CollectionRequests $request
     * @return mixed
     */
    public function delCollection(CollectionRequests $request)
    {
        // 检查模块类型
        UserCollection::checkModularType($request->modular_type);
        // 删除收藏
        UserCollection::del($request);

        return $this->success();
    }

    /**
     * 获取收藏
     */
    public function getCollection(CollectionRequests $request)
    {
        if ($request->modular_type) { // 查询对应模块收藏

            // 检查模块类型
            UserCollection::checkModularType($request->modular_type);
            // 查询收藏信息
            $re = UserCollection::modularTypeCollectionInfo($request->modular_type);

        } else { // 查询所有收藏

            // 微信收藏
            $re['weixin'] = UserCollection::modularTypeCollectionInfo('WEIXIN');
            // 微博收藏
            $re['weibo'] = UserCollection::modularTypeCollectionInfo('WEIBO');
            // 视频收藏
            $re['video'] = UserCollection::modularTypeCollectionInfo('VIDEO');
            // 自媒体收藏
            $re['selfmedia'] = UserCollection::modularTypeCollectionInfo('SELFMEDIA');
            // 软文收藏
            $re['softarticle'] = UserCollection::modularTypeCollectionInfo('SOFTARTICLE');

        }

        return $this->success($re);
    }

}