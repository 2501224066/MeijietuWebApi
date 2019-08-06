<?php


namespace App\Http\Controllers\v1;


use App\Jobs\SendSms;
use App\Models\Data\Goods;
use App\Models\Data\GoodsPrice;
use App\Server\Captcha;

class TestController extends BaseController
{

    function index()
    {

        $goodsId = '108698';
        $goodsData = Goods::with(['one_goods_price' => function ($query) use ($goodsId) {
            $query->where('goods_price_id', GoodsPrice::whereGoodsId($goodsId)->value('goods_price_id'));
        }])
            ->where('goods_id', $goodsId)
            ->first()
        ->toArray();

        dd($goodsData);

        // TODO...
    }
}
