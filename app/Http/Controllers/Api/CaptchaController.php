<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Captcha as CaptchaRequests;
use App\Models\Captcha;;
use Gregwar\Captcha\CaptchaBuilder;
use Cache;

class CaptchaController extends BaseController
{
    /**
     * 发送邮箱验证码
     */
    public function emailVerifCode(CaptchaRequests $request)
    {
        $code = Captcha::createAndKeepCode($request->code_type, $request->email);
        Captcha::sendEmailVerifCode($request->email, $request->code_type, $code);

        return $this->success('邮件发送成功，请注意查收');
    }

    /**
     * 发送短信验证码
     */
    public function smsVerifCode(CaptchaRequests $request)
    {
        $code = Captcha::createAndKeepCode($request->code_type, $request->phone);
        Captcha::sendSmsVerifCode($request->phone, $code);

        return $this->success('短信验证码发送成功，请注意查收');
    }

    /**
     * 生成图形验证码
     */
    public function getImgCode(CaptchaRequests $request)
    {
        $builder = new CaptchaBuilder;
        $builder->build(100, 40);
        $code = $builder->getPhrase();
        
        $key = 'imgCode:'.$request->imgToken;
        Cache::put($key, $code, 5);
       
        $output = $builder->output();
        return response($output, 200)
            ->header("Cache-Control", " no-cache, must-revalidate")
            ->header('Content-Type', 'image/jpeg');
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

