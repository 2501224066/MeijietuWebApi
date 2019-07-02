<?php


namespace App\Http\Controllers\Api;

use App\Models\Nb\Goods;

class TestController extends BaseController
{
    // 商品上架
    public function openGoods()
    {
        Goods::whereVerifyStatus(0)->update([
            'verify_status' => 2,
            'status'        => 1,
        ]);

        return $this->success();
    }
}