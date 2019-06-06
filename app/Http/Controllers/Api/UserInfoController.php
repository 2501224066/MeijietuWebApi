<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\UserInfo as UserInfoRequests;
use App\Models\Captcha;
use App\Models\RealnameEnterprise;
use App\Models\RealnamePeople;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserInfoController extends BaseController
{
    /**
     * 个人实名认证
     * @param UserInfoRequests $request
     * @return mixed
     */
    public function realnamePeople(UserInfoRequests $request)
    {
        // 检查是否已经认证
        User::checkRealnameStatus();
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
     * 获取个人实名认证信息
     * @return mixed
     */
    public function realnamePeopleInfo()
    {
        return $this->success(RealnamePeople::info());
    }

    /**
     * 企业实名认证
     * @param UserInfoRequests $request
     * @return mixed
     */
    public function realnameEnterprise(UserInfoRequests $request)
    {
        // 检查是否已经认证
        User::checkRealnameStatus();
        // 绑定手机号检验
        Captcha::checkCode($request->smsCode, $request->bank_band_phone, 'realnameEnterprise');
        // 检查营业执照信息
        RealnameEnterprise::checkBusinessLicense($request->business_license, $request->enterprise_name, $request->social_credit_code);
        // 数据存入数据库
        RealnameEnterprise::add($request);

        return $this->success('企业实名认证成功');
    }

    /**
     * 获取企业实名认证信息
     * @return mixed
     */
    public function realnameEnterpriseInfo()
    {
        return $this->success(RealnameEnterprise::info());
    }

    /**
     * 修改用户信息
     * @param UserInfoRequests $request
     * @return mixed
     */
    public function saveInfo(UserInfoRequests $request)
    {
        // 检查图形验证码
        Captcha::checkCode($request->imgCode, $request->imgToken, 'imgCode');
        // 修改信息并记录
        User::saveInfo($request);

        return $this->success('修改完成');
    }

    /**
     * 修改手机号
     * @param UserInfoRequests $request
     * @return mixed
     */
    public function savePhone(UserInfoRequests $request)
    {
        // 检查手机号是否为当前用户手机号
        User::checkUserPhone($request->phone);
        // 检查短信验证码
        Captcha::checkCode($request->smsCode, $request->phone, 'savePhone');
        // 修改手机号并记录
        User::savePhoneAndLog($request->phone, $request->new_phone);

        return $this->success('修改完成');
    }

    /**
     * 修改密码
     * @param UserInfoRequests $request
     * @return mixed
     */
    public function savePass(UserInfoRequests $request)
    {
        $phone = JWTAuth::user()->phone;
        // 检查短信验证码
        Captcha::checkCode($request->smsCode, $phone, 'savePhone');
        // 检查密码
        User::checkPass($phone, $request->password);
        // 修改密码
        User::savePass($phone, $request->new_pass);

        return $this->success('修改完成');
    }
}