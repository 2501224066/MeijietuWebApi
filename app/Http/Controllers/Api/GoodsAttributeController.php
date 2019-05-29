<?php


namespace App\Http\Controllers\Api;

use App\Models\Weixin\Theme;

class GoodsAttributeController extends BaseController
{
    /**
     * 商品属性
     */
    public function weixinGoodsAttribute()
    {
        $re = Theme::with('filed')
            ->with('fansnumlevel')
            ->with('readlevel')
            ->with('likelevel')
            ->with('priceclassify')
            ->get()
            ->toArray();

        return $this->success($re);
    }
}