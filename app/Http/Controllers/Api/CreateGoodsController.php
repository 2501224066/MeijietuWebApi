<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateGoods as CreateGoodsRequests;
use App\Jobs\getWeixinGongZhongHaoBasicData;
use App\Models\Weixin\GoodsWeixin;
use App\Models\Weixin\Theme;
use App\Models\Weibo\GoodsWeibo;
use App\Models\Softarticle\GoodsSoftarticle;
use App\Models\Selfmedia\GoodsSelfmedia;

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
        $goods_weixin_id = GoodsWeixin::add($request);

        // 【队列】 如果主题为公众号放入队列查询微信公众号基本信息
        switch (Theme::whereThemeId($request->theme_id)->value('theme_name')) {
            case '微信公众号':
                getWeixinGongZhongHaoBasicData::dispatch($goods_weixin_id, $request->weixin_ID)->onQueue('getWeixinGongZhongHaoBasicData');
                break;
        }

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
        $goods_weibo_id = GoodsWeibo::add($request);

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
        $goods_video_id = GoodsSoftarticle::add($request);

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
        $goods_selfmedia_id = GoodsSelfmedia::add($request);

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
        $goods_softarticle_id = GoodsSoftarticle::add($request);

        return $this->success();
    }
}