<?php


namespace App\Http\Controllers\Api;

use App\Models\Nb\Goods;
use App\Models\Tb\Theme;
use Illuminate\Support\Facades\DB;

class TestController extends BaseController
{
    // 商品上架
    public function openGoods()
    {
        $e = Goods::whereUid(0)
        ->where('theme_name', '公众号')
        ->where('weixin_ID', 'q153203229')
        ->pluck('goods_id');
    dd($e);

        /*Goods::whereVerifyStatus(0)->update([
            'verify_status' => 2,
            'status'        => 1,
        ]);

        return $this->success();*/
    }
}