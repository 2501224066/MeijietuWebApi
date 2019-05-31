<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateGoods as CreateGoodsRequests;
use App\Models\Weibo\GoodsWeixin;

class CreateGoodsController extends BaseController
{
    public function createWeixinGoods(CreateGoodsRequests $request)
    {
        // 添加微信商品
        GoodsWeixin::add($request);

        return $this->success();
    }

}