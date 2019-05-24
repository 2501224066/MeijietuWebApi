<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\UserInfo as UserInfoRequests;
use App\Models\RealnamePeople;

class UserInfoController extends BaseController
{
    /**
     * 个人实名认证
     */
    public function realnamePeople(UserInfoRequests $request)
    {
        RealnamePeople::checkBankInfo($request->truename, $request->bank_card, $request->identity_card_ID, $request->bank_band_phone); // 检查银行卡信息
        // 检查身份证信息
        // do ...

        return $this->success();
    }
}