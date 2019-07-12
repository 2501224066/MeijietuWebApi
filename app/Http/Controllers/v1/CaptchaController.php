<?php

namespace App\Http\Controllers\v1;

use App\Http\Requests\Captcha as CaptchaRequests;
use App\Jobs\SendEmail;
use App\Jobs\SendSms;
use App\Models\Captcha;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Cache;

class CaptchaController extends BaseController
{
    /**
     * 发送邮箱验证码
     */
    public function emailVerifCode(CaptchaRequests $request)
    {
        $code = Captcha::createAndKeepCode($request->code_type, $request->email);
        SendEmail::dispatch(Captcha::TYPE['验证码'], $request->email, $code)->onQueue('SendEmail');

        return $this->success();
    }

    /**
     * 发送短信验证码
     */
    public function smsVerifCode(CaptchaRequests $request)
    {
        $code = Captcha::createAndKeepCode($request->code_type, $request->phone);
        SendSms::dispatch(Captcha::TYPE['验证码'], $request->phone, $code)->onQueue('SendSms');

        return $this->success();
    }

    /**
     * 生成图形验证码
     */
    public function getImgCode()
    {
        $builder = new CaptchaBuilder;
        $builder->build(100, 40);
        $code = $builder->getPhrase();

        $imgToken = mt_rand(100000, 999999);
        $key      = 'imgCode:' . $imgToken;
        Cache::put($key, $code, 5);

        $base64Img = $builder->inline();
        return $this->success([
            'img_token' => $imgToken,
            'img_code'  => $base64Img
        ]);
    }

    /**
     * 验证图形验证码
     */
    public function checkImgCode(CaptchaRequests $request)
    {
        Captcha::checkCode($request->imgCode, $request->imgToken, 'imgCode');

        return $this->success();
    }
}

