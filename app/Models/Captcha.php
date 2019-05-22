<?php

namespace App\Models;

use App\Mail\emailVerifCode;
use Cache;
use Mockery\Exception;
use Illuminate\Support\Facades\Mail;
use Mrgoon\AliSms\AliSms;

class Captcha
{
    //请求验证码类型
    const CODETYPE = [
        'checkPhone' => '检查手机号',
        'resetPassCode' => '重置密码'
    ];

    //检查请求验证码类型
    public static function checkCodeType($codeType)
    {
        if( ! in_array($codeType, array_keys(self::CODETYPE) ) )
            throw new Exception('请求验证码类型错误');
            
        return true;
    }

    //生成并存储验证码
    public static function createAndKeepCode($codeType, $parm, $randstr = false)
    {
        self::checkCodeType($codeType);


        $code = $randstr ? md5(uniqid()) : rand(111111, 999999);
        $key = $codeType.':'. $parm;
        Cache::put($key, $code, 5);

        return $code;
    }

    //验证验证码
    public static function checkCode($code, $parm, $codeType)
    {
        //return true;
        
        $key = $codeType.":".$parm;
        if( ! Cache::has($key) )
            throw new Exception('验证码过期');
        
        if(Cache::get($key) !== $code)
            throw new Exception('验证码错误');

        return true;

    }

    //发送邮箱验证码
    public static function sendEmailVerifCode($email, $code_type, $code)
    {
        $re = Mail::to($email)
            ->cc(ENV('MAIL_FROM_ADDRESS'))
            ->send(new emailVerifCode(self::CODETYPE[$code_type], $code));
        if ( ! $re)
            throw new Exception('邮箱验证码发送失败');

       return true;
    }

    //发送短信验证码
    public static function sendSmsVerifCode($phone, $code)
    {
        $ali_sms = new AliSms();
        $re = $ali_sms->sendSms($phone, env('ALIYUN_SMS_TEMPLATE_CODE'), ['code' => $code]);

        if ( ! ($re->Message == "OK"))
            throw new Exception('短信验证码发送失败');
    }
}