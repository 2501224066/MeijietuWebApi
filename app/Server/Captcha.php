<?php

namespace App\Server;


use App\Server\Pub;
use Cache;
use Mockery\Exception;

class Captcha
{
    const TYPE = [
        '验证码'  => 1,
        '订单通知' => 2,
        '资金变动' => 3
    ];

    //检查请求验证码类型
    public static function checkCodeType($codeType)
    {
        if (!in_array($codeType, Pub::CODE_TYPE))
            throw new Exception('请求验证码类型错误');

        return true;
    }

    /**
     * 生成并存储验证码
     * @param string $codeType 验证码类型
     * @param string $parm 标记参数
     * @param bool $randstr 是否为字母
     * @return string
     */
    public static function createAndKeepCode($codeType, $parm, $randstr = false): string
    {
        self::checkCodeType($codeType);

        $code = $randstr ? str_random(30) : rand(100000, 999999);
        $key  = $codeType . ':' . $parm;
        Cache::put($key, $code, 5);

        return $code;
    }

    /**
     * 验证验证码
     * @param string $code 验证码
     * @param string $parm 标记参数
     * @param string $codeType 验证码类型
     */
    public static function checkCode($code, $parm, $codeType)
    {
        $key = $codeType . ":" . $parm;
        if (!Cache::has($key))
            throw new Exception('【验证码/令牌】过期');

        if (Cache::get($key) != $code)
            throw new Exception('【验证码/令牌】错误');
    }
}