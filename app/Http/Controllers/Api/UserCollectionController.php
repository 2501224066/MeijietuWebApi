<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\UserCollection as UserCollectionRequests;
use App\Models\User;
use App\Models\UserCollection;
use App\Service\ModularData;

class UserCollectionController extends BaseController
{
    /**
     * 收藏商品
     * @param UserCollectionRequests $request
     * @return mixed
     */
    public function collectionGoods(UserCollectionRequests $request)
    {
        // 判断用户身份
        User::checkIdentity();
        // 检查模块类型
        ModularData::checkModularType($request->modular_type);
        // 判断商品是否已经收藏
        UserCollection::checkCollectionHas($request);
        // 判断商品是否存在
        ModularData::checkGoodsHas($request->modular_type, $request->goods_id);
        // 添加收藏
        UserCollection::add($request);

        return $this->success();
    }

    /**
     * 删除收藏
     * @param UserCollectionRequests $request
     * @return mixed
     */
    public function delCollection($idStr)
    {
        $idArr = explode('-', trim($idStr));
        UserCollection::del($idArr);

        return $this->success();
    }

    /**
     * 获取收藏
     * @param UserCollectionRequests $request
     * @return mixed
     */
    public function getCollection(UserCollectionRequests $request)
    {
        if ($request->modular_type) {
            // 查询对应模块收藏
            ModularData::checkModularType($request->modular_type); // 检查模块类型
            $re = UserCollection::modularTypeCollectionInfo($request->modular_type);// 查询收藏信息
        } else {
            // 查询所有收藏
            $re['weixin']      = UserCollection::modularTypeCollectionInfo('WEIXIN');      // 微信收藏
            $re['weibo']       = UserCollection::modularTypeCollectionInfo('WEIBO');       // 微博收藏
            $re['video']       = UserCollection::modularTypeCollectionInfo('VIDEO');       // 视频收藏
            $re['selfmedia']   = UserCollection::modularTypeCollectionInfo('SELFMEDIA');   // 自媒体收藏
            $re['softarticle'] = UserCollection::modularTypeCollectionInfo('SOFTARTICLE'); // 软文收藏
        }

        return $this->success($re);
    }

}