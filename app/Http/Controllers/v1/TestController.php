<?php


namespace App\Http\Controllers\v1;


use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;

class TestController extends BaseController
{

    function index()
    {
        // ToDo...
        // 充钱
        //$uid = 1;
        //$avaiable_money = 1000000;
        //$time = '2019-07-15 16:16:25';
        //return md5($uid . substr(env('WALLET_SALT'), 13, 28) . substr(env('WALLET_SALT'), 45, 51) . $avaiable_money * 1 . $time);

//        Goods::wherePlatformName('小红书')->update([
//            'filed_id'=> 42,
//            'filed_name' => '其他'
//        ]);

        echo GoodsPrice::wherePrice(5000)->count();
    }



}