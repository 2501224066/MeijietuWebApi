<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\UserInfo as UserInfoRequests;
use App\Models\Captcha;
use App\Models\RealnamePeople;

class UserInfoController extends BaseController
{
    /**
     * 个人实名认证
     */
    public function realnamePeople(UserInfoRequests $request)
    {
        Captcha::checkCode($request->smsCode, $request->bank_band_phone, 'realnamePeople'); // 绑定手机号检验
        RealnamePeople::checkBankInfo($request->truename, $request->bank_card, $request->identity_card_ID, $request->bank_band_phone); // 检查银行卡信息
        RealnamePeople::IDcheck($request->identity_card_face, $request->truename); // 证件识别
        RealnamePeople::add($request); // 数据存入数据库

        return $this->success('实名认证成功');
    }

    /**
     * 企业实名认证
     */
    public function realnameEnterprise(UserInfoRequests $request)
    {
        Captcha::checkCode($request->smsCode, $request->bank_band_phone, 'realnameEnterprise'); // 绑定手机号检验
    }
}