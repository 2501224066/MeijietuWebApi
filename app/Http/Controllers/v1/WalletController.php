<?php


namespace App\Http\Controllers\v1;


use App\Models\Up\Wallet;
use Tymon\JWTAuth\Facades\JWTAuth;

class WalletController extends BaseController
{
    // 创建钱包
    public function createWallet()
    {
        $uid = JWTAuth::user()->uid;
        // 检测是否已经拥有
        Wallet::checkHas($uid, FALSE);
        // 创建
        Wallet::createWallet($uid);

        return $this->success();
    }

    // 钱包信息
    public function walletInfo()
    {
        $re = Wallet::info();

        return $this->success($re);
    }
}