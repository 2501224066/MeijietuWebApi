<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\SelectGoods as SelectGoodsRequests;
use App\Models\Weixin\GoodsWeixin;
use App\Models\Weixin\GoodsWeixinPrice;

class SelectGoodsController extends BaseController
{
    /**
     *
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
}