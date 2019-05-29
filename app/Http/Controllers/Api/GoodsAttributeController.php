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
            ->with('authtype')
            ->with('priceclassify')
            ->get();

        return $this->success($re);
    }
}