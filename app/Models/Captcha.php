<?php

namespace App\Models;


use App\Service\Pub;
use Cache;
use Mockery\Exception;

class Captcha
{
    const TYPE = [
        '验证码' => 1
    ];

    //检查请求验证码类型
    public static function checkCodeType($codeType)
    {
        if (!in_array($codeType, Pub::CODE_TYPE))
            throw new Exception('请求验证码类型错误');

        return true;
    }

    //生成并存储验证码
    public static function createAndKeepCode($codeType, $parm, $randstr = false)
    {
        self::checkCodeType($codeType);

        $code = $randstr ? str_random(30) : rand(100000, 999999);
        $key  = $codeType . ':' . $parm;
        Cache::put($key, $code, 5);

        return $code;
    }

    //验证验证码
    public static function checkCode($code, $parm, $codeType)
    {
        // 测试
        return true;

        $key = $codeType . ":" . $parm;
        if (!Cache::has($key))
            throw new Exception('【验证码/令牌】过期');

        if (Cache::get($key) !== $code)
            throw new Exception('【验证码/令牌】错误');

        return true;
    }
}