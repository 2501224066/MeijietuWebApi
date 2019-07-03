<?php


namespace App\Http\Controllers\Api;

use App\Models\Nb\Goods;
use App\Service\Pub;

class TestController extends BaseController
{
    public function index()
    {
        /*
        // 商品上架
        Goods::whereVerifyStatus(0)->update([
            'verify_status' => 2,
            'status'        => 1,
        ]);*/

        return $this->success();
    }
}