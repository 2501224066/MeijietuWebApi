<?php


namespace App\Http\Controllers\Api;


use App\Models\Nb\Goods;

class TestController extends BaseController
{

    function index()
    {
        // ToDo...
        // 充钱
        /*$uid = 1000025;
        $avaiable_money = 1000000;
        $time = '2019-06-27 17:27:39';
        return md5($uid . substr(env('WALLET_SALT'), 13, 28) . substr(env('WALLET_SALT'), 45, 51) . $avaiable_money * 1 . $time);*/

        // 上架
//        Goods::whereVerifyStatus(0)->update([
//            'status'=> 1,
//            'verify_status' =>2
//        ]);
    }



}