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
        $s= [];
        if(!$s){
            echo 'sdsad';
        }
        print_r($s==false);
        exit;

        Goods::whereVerifyStatus(0)->update([
            'verify_status' => 2,
            'status'        => 1,
        ]);

        return $this->success();
    }
}