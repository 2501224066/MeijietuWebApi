<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\UserInfo as UserInfoRequests;
use App\Models\Captcha;
use App\Models\RealnameEnterprise;
use App\Models\RealnamePeople;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserInfoController extends BaseController
{
    /**
     * 个人实名认证
     */
    public function realnamePeople(UserInfoRequests $request)
    {
        // 绑定手机号检验
        Captcha::checkCode($request->smsCode, $request->bank_band_phone, 'realnamePeople');
        // 检查银行卡信息
        RealnamePeople::checkBankInfo($request->truename, $request->bank_card, $request->identity_card_ID, $request->bank_band_phone);
        // 证件识别
        RealnamePeople::IDcheck($request->identity_card_face, $request->truename);
        // 数据存入数据库
        RealnamePeople::add($request);

        return $this->success('个人实名认证成功');
    }

    /**
     * 企业实名认证
     */
    public function realnameEnterprise(UserInfoRequests $request)
    {
        // 绑定手机号检验
        Captcha::checkCode($request->smsCode, $request->bank_band_phone, 'realnameEnterprise');
        // 检查营业执照信息
        RealnameEnterprise::checkBusinessLicense($request->business_license, $request->enterprise_name, $request->social_credit_code);
        // 数据存入数据库
        RealnameEnterprise::add($request);

        return $this->success('企业实名认证成功');
    }

    /**
     * 修改用户信息
     */
    public function saveUserInfo()
    {

    }

}