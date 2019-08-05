<?php


namespace App\Http\Controllers\v1;


use App\Models\Data\News;
use App\Models\Pay\Wallet;
use Tymon\JWTAuth\Facades\JWTAuth;

class WalletController extends BaseController
{
    // 创建钱包
    public function createWallet()
    {
        $uid = JWTAuth::user()->uid;
        // 创建
        Wallet::createWallet($uid);
        // 消息推送
        News::put($uid, "资金账户创建成功");

        return $this->success();
    }

    // 钱包信息
    public function walletInfo()
    {
        $re = Wallet::info();

        return $this->success($re);
    }
}