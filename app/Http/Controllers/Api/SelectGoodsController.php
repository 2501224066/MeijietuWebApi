<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\SelectGoods as SelectGoodsRequests;
use App\Models\Weixin\GoodsWeixin;
use App\Models\Weixin\GoodsWeixinPrice;
use App\Models\Weibo\GoodsWeibo;
use App\Models\Weibo\GoodsWeiboPrice;
use App\Models\Video\GoodsVideo;
use App\Models\Video\GoodsVideoPrice;
use App\Models\Selfmedia\GoodsSelfmedia;

class SelectGoodsController extends BaseController
{
    /**
     * 搜索微信商品
     * @param SelectGoodsRequests $request
     * @return mixed
     */
    public function selectWeixinGoods(SelectGoodsRequests $request)
    {
        // 价格筛选
        $idArr = GoodsWeixinPrice::screenPrice($request);

        // 拼装条件并查询
        $data = GoodsWeixin::select($request, $idArr);

        // 插入价格信息
        $re = GoodsWeixinPrice::withPriceInfo($data);

        return $this->success($re);
    }

    /**
     * 搜索微博商品
     * @param SelectGoodsRequests $request
     * @return mixed
     */
    public function selectWeiboGoods(SelectGoodsRequests $request)
    {
        // 价格筛选
        $idArr = GoodsWeiboPrice::screenPrice($request);

        // 拼装条件并查询
        $data = GoodsWeibo::select($request, $idArr);

        // 插入价格信息
        $re = GoodsWeiboPrice::withPriceInfo($data);

        return $this->success($re);
    }

    /**
     * 搜索视频商品
     * @param SelectGoodsRequests $request
     * @return mixed
     */
    public function selectVideoGoods(SelectGoodsRequests $request)
    {
        //// 价格筛选
        $idArr = GoodsVideoPrice::screenPrice($request);

        // 拼装条件并查询
        $data = GoodsVideo::select($request, $idArr);

        // 插入价格信息
        $re = GoodsVideoPrice::withPriceInfo($data);

        return $this->success($re);
    }

    /**
     * 搜索自媒体商品
     * @param SelectGoodsRequests $request
     * @return mixed
     */
    public function selectSelfmediaGoods(SelectGoodsRequests $request)
    {
        // 拼装条件并查询
        $re = GoodsSelfmedia::select($request);

        return $this->success($re);
    }
}