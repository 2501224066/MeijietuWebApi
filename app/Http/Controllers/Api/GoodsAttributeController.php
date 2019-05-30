<?php


namespace App\Http\Controllers\Api;


class GoodsAttributeController extends BaseController
{
    /**
     * 微信商品属性
     * @return mixed
     */
    public function weixinGoodsAttribute()
    {
        $re = \App\Models\Weixin\Theme::with('filed')
            ->with('fansnumlevel')
            ->with('readlevel')
            ->with('likelevel')
            ->with('priceclassify')
            ->get();

        return $this->success($re);
    }

    /**
     * 微博商品属性
     * @return mixed
     */
    public function weiboGoodsAttribute()
    {
        $re = \App\Models\Weibo\Theme::with('filed')
            ->with('fansnumlevel')
            ->with('authtype')
            ->with('priceclassify')
            ->get();

        return $this->success($re);
    }

    /**
     * 视频商品属性
     * @return mixed
     */
    public function videoGoodsAttribute()
    {
        $re = \App\Models\Video\Theme::with('filed')
            ->with('fansnumlevel')
            ->with('platform')
            ->with('priceclassify')
            ->get();

        return $this->success($re);
    }

    /**
     * 自媒体商品属性
     * @return mixed
     */
    public function selfmediaGoodsAttribute()
    {
        $re = \App\Models\Selfmedia\Theme::with('filed')
            ->with('fansnumlevel')
            ->with('platform')
            ->get();

        return $this->success($re);
    }

    /**
     * 软文商品属性
     * @return mixed
     */
    public function softarticleGoodsAttribute()
    {
        $re = \App\Models\Softarticle\Theme::with('filed')
            ->with('pricelevel')
            ->with('platform')
            ->with('entryclassify')
            ->with('industry')
            ->with('sendspeed')
            ->get();

        return $this->success($re);
    }
}