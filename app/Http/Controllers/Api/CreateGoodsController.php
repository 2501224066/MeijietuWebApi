<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateGoods as CreateGoodsRequests;
use App\Jobs\getWeixinGongZhongHaoBasicData;
use App\Models\Weixin\GoodsWeixin;
use App\Models\Weixin\Theme;
use App\Models\Weibo\GoodsWeibo;
use App\Models\Softarticle\GoodsSoftarticle;
use App\Models\Selfmedia\GoodsSelfmedia;
use App\Models\Video\GoodsVideo;

class CreateGoodsController extends BaseController
{
    /**
     * 添加微信商品
     * @param CreateGoodsRequests $request
     * @return mixed
     */
    public function createWeixinGoods(CreateGoodsRequests $request)
    {
        // 数据入库
        $goods_id = GoodsWeixin::add($request);

        // 【队列】 公众号->插入基础信息
        if(Theme::whereThemeId($request->theme_id)->value('theme_name') == '微信公众号')
            getWeixinGongZhongHaoBasicData::dispatch($goods_id, $request->weixin_ID)->onQueue('getWeixinGongZhongHaoBasicData');

        return $this->success();
    }

    /**
     * 添加微博商品
     * @param CreateGoodsRequests $request
     * @return mixed
     */
    public function createWeiboGoods(CreateGoodsRequests $request)
    {
        // 数据入库
        $goods_id = GoodsWeibo::add($request);

        // 【队列】  插入基础信息
        // TODO...

        return $this->success();
    }

    /**
     * 添加视频商品
     * @param CreateGoodsRequests $request
     * @return mixed
     */
    public function createVideoGoods(CreateGoodsRequests $request)
    {
        // 数据入库
        GoodsVideo::add($request);

        return $this->success();
    }

    /**
     * 添加自媒体商品
     * @param CreateGoodsRequests $request
     * @return mixed
     */
    public function createSelfmediaGoods(CreateGoodsRequests $request)
    {
        // 数据入库
        GoodsSelfmedia::add($request);

        return $this->success();
    }

    /**
     * 添加软文商品
     * @param CreateGoodsRequests $request
     * @return mixed
     */
    public function createSoftarticleGoods(CreateGoodsRequests $request)
    {
        // 数据入库
        GoodsSoftarticle::add($request);

        return $this->success();
    }
}