<?php


namespace App\Http\Controllers\Api;


use App\Models\Nb\Goods;

class TestController extends BaseController
{

    function index()
    {
        // ToDo...
        // 充钱
        //$uid = 1000036;
        //$avaiable_money = 1000000;
        //$time = '2019-07-09 12:11:33';
        //return md5($uid . substr(env('WALLET_SALT'), 13, 28) . substr(env('WALLET_SALT'), 45, 51) . $avaiable_money * 1 . $time);

        // 上架
//        Goods::whereVerifyStatus(0)->update([
//            'status'=> 1,
//            'verify_status' =>2
//        ]);
    }



}