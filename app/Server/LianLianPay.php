<?php


namespace App\Server;

use App\Models\Pay\Runwater;
use App\Models\Pay\Wallet;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class LianLianPay
{
    // 商户号
    private static $oid_partner = '201906120002511007';

    // 商户管理员手机号
    private static $phone = '3044889256';

    // 私钥
    private static $priKey = "-----BEGIN RSA PRIVATE KEY-----" . "\n" .
    "MIIJKAIBAAKCAgEAliWcq2i9Ye0etuEJKKo81Vq9LBvbODimqW+gtU8VManEpaQa" . "\n" .
    "eGI9Y858s2klSJiWnLISq3Iovggxdp0RzKCuopjKDuUbCBwQ13u85YpXRrr/fSi5" . "\n" .
    "GaiwFEGuEmZmyldzYAjG2+Pf/mpsttAmtpxLqcCt6Q8qDB5MiNT9Hc8cTQVerkdT" . "\n" .
    "1/oqvzOsl216idTEQcggB7XyfFZeUGFZy3ypU9hCAbG6sICpfq4BNbqQBTjW4VUW" . "\n" .
    "4fmsBPzxkQta8iwZdTZ8oqDR3eVo3FvNnz0zs+smmPkjkqutCzSUxShF332SfXvW" . "\n" .
    "8wopmxl48M+uwyjZhYiPV7s9pgV8nM057YUFJ+bIxr1b8t30p1eUaYVI7sZlc0BH" . "\n" .
    "b6OanbY634XbOG9yk/nd9ZVarZs1V+WuXxxiZ/LdIU7gQvfhWbIst2jNVwHJVsUD" . "\n" .
    "s09z0Y0lCc+ZSjKi8bvQw62Pu6JnQgYQKKyeIzAm9osxZsNfeWliDG+OHe3a8a+w" . "\n" .
    "BBYkz9BtKM8yPTMdy5h89FvCOhtMJpGlMNqAm/DXhlGJzWAZW2yQOmzioPknQll4" . "\n" .
    "wiDfIQxbPUzDHC9ed7dBLA2el86AcYt4+z5x5iAaNgcMx8MNtf/0U0UkX4rwi/wu" . "\n" .
    "Up19O1gn6VGCF9uzF90Or1Uip2GX21rosmTaNmwj/Y09vdl0lAWemqiT1VcCAwEA" . "\n" .
    "AQKCAgBjgGN1+IBgsApFuZYfO8n6vhpVa9R4Spqv+Ijw/oqWEeJ+vuTH2JrEVKex" . "\n" .
    "IcISfQR6rUvQEGRNoxJcf7S2/dkdadGl01B5rFfA5YCGQYqbFfuQTvdzuKWPlwMr" . "\n" .
    "sG74MsID520ZdccQTCjlccXmCGfgBA98Po9n3oBrwXJIWeNwa1mWdzBqFaBUe4Mq" . "\n" .
    "CuRFCRpSWbRYR8x9d6Mi85DVWDTCmrMLemO06l7o05l7qLoeq89DHud+M746xAG2" . "\n" .
    "VGcdy/nHAqfQAnFx8sDuH2yB1cY4umImyBw568lOfrcEfS7o78evnu0QvTBbV6Md" . "\n" .
    "OxSewqh8ZMZRpoUxS7uMEjkMXbXO+rixDwdkZQQkROjrJ8FiuNVkQsmtfYuXZ4Ua" . "\n" .
    "LWt2yRY2AvAq/qtPsQUb605EEE9/B26pPZwoUBqPqhCrGRrmtLCOwQIUrZJfXEVb" . "\n" .
    "bDbhs1d3vE+bQMxkoIfRI7l+TxMSA3aul7o8afIh64Zbf2KDSSM488ljLdcLyEIs" . "\n" .
    "cbrtuccdlnDJOWlw/jXZDKIP+ryrrJa7B4eYgfsDU6hVTLbT97xv9BICzxDODiGP" . "\n" .
    "C4QjGr+EnpZQ42oDPK7TjIX2AueibfSdyCCvDEqTWVtK61LPl6YC9+nr9+bzdoA1" . "\n" .
    "ownKcSGInQbHP6mQ0LMY3Qy/pX/UAEXTPWNS0I68etIJ2fpRsQKCAQEAxBR7zkdE" . "\n" .
    "q//SvbBkJlI/wCuyMyMhIoJCKkXEVtZjscE5LQCh5MjNoRPzpH46wCt3xJvgpl4b" . "\n" .
    "bUJduD57kTNp3pEvCXN54gWkhVS/Dh7p2ty2JCQHKV2xAKGwVxhQY/T1o2oUp/Bt" . "\n" .
    "3pUyugGun+7m6w/NIL+quUiEWXi69ZNanLos1w/402IRzFflyK0LyymiBOiXA2hG" . "\n" .
    "ZwgaLuWXru61PCPAkR0qYYf1ZQILFBhVyy/q4/y3WOPPn0MUZ2p/I9EaYnyVNwDu" . "\n" .
    "Y5jOsE2ogmdzpwzYj7j/RRF0fCjrZYxaepHm5aQs8qviBP5ajBCVWxI162W3GiQg" . "\n" .
    "F2cVQvB058/i2QKCAQEAxAe9uqi+1qqhRzn3ieg1yW+X0yWpJawi9zQE28S0XZur" . "\n" .
    "KGdVQ7XJk40eQA4kEuoSuOqwca6j11lp+VnaVpcjrlHmPJA+bY9MBoG6LibDwbIj" . "\n" .
    "ZzP5bsV50AvPDAT4tKNlHnwXOUohnfo3U4WyErZGl4Yr/Dmd48g9BAOdZpzXy+lK" . "\n" .
    "lgdk+IBBid0fM6bK3WkIx6af2908AqYNK/AsGuCc1p9c4XNTCS6ZPi7EZvdyHSCB" . "\n" .
    "6H/bXtY+/naOT1eUAzSwwi2AGyrybJv6zelOvU4PV6PocDs7IdhchYbsB94PISDZ" . "\n" .
    "1QCPxizCCSQnax53CFWWrJM5rlpsjt/NLqmp7FX7rwKCAQBqVcVyb5nhQSIzdrZJ" . "\n" .
    "Re+Gsuu36cQtZ8CkFiPNCAUWv/fW3PHvFarWQPCvczk/QBN6jyI8duqezQ/wPApd" . "\n" .
    "CynN5qRAltwxY+f92qCl8sPVyj4IyXUMzN+RvR3ZjbkDDDQuQ/ghvzSGMhlJYGIo" . "\n" .
    "H7IhJfeTW/4k7xu/Pcb+KMRKHHj2xJWnZRbL35sgwh+UNObFjLFgyqrrcyxn5GDn" . "\n" .
    "IFMu4yCqUcvLCqVc+sexRSU5xKm4rDNuRFUzRHiLQOjkLqEFahStuJaPcq4cVHEf" . "\n" .
    "DyVcIY5MGwJsnXOzZamK75udvwQd43PwwqAXHy1RqiilM/IDz6yFw7OXnXKdU7PC" . "\n" .
    "hy25AoIBAQC+L4B8u0DDhjIIp42o53hfoXvcZ/puSU53JandCj+vXds1zOMlWRZj" . "\n" .
    "qBMENHP9Qcm+TDu5X6UnxmERgYJaZjJA6saEVbGrm843td7K7eMYZlsAwqUqOj8P" . "\n" .
    "gJV05NHid3AgnmXtuCVbXqoUx25Xxy4/zfWYtNGhb+D6pwMrzdlzxTgOzMfCbLSp" . "\n" .
    "K7Yc56KEI2ZYRSltJ3wUWcQHr/EqdmbTtzNmq/uQufwFH1x/RW0aIzLGPl236gad" . "\n" .
    "3Kn/AkngvlsLWRejxAwFIWO4KLWy5ivubGCTmnOpR3+kMJ2ImIF3M6cDh5L1wvDR" . "\n" .
    "65iuzZTQqjEP55qbtLEEAM/RiNwKJfWTAoIBAF8FJ1DWCYEj1l8r7d9PzY+kZWVv" . "\n" .
    "UudapvCD8oj8eQpBGmiBDKnitqopLCJg35bP1CcO3mzr088Zpwo0DrSX7m1iJqyn" . "\n" .
    "MbvBHFtK1TyHJdEwZa3AFCvmGq7wgi4SZpdKxDGhqGhtyTLF7t51Y+lZq6yI6NIb" . "\n" .
    "hmYs83LrTrs1aDN900XhqNCTbg2rVLx2A+MvWHoyeHP/MzDlTKAZqNcz7QHJxzuH" . "\n" .
    "ikSWMVd7BolL4zTRktyfptkYeUwxvD8q7yJB6juqr7GlIUc0eZEHNXznzGdh3YIA" . "\n" .
    "829mP57rAnxI0NslARxnP4ICcWg1hizXMO/bZRShaKWWljJ60E55EiaEVqA=" . "\n" .
    "-----END RSA PRIVATE KEY-----";

    // 公钥
    private static $publicKey = 'MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAliWcq2i9Ye0etuEJKKo81Vq9LBvbODimqW+gtU8VManEpaQaeGI9Y858s2klSJiWnLISq3Iovggxdp0RzKCuopjKDuUbCBwQ13u85YpXRrr/fSi5GaiwFEGuEmZmyldzYAjG2+Pf/mpsttAmtpxLqcCt6Q8qDB5MiNT9Hc8cTQVerkdT1/oqvzOsl216idTEQcggB7XyfFZeUGFZy3ypU9hCAbG6sICpfq4BNbqQBTjW4VUW4fmsBPzxkQta8iwZdTZ8oqDR3eVo3FvNnz0zs+smmPkjkqutCzSUxShF332SfXvW8wopmxl48M+uwyjZhYiPV7s9pgV8nM057YUFJ+bIxr1b8t30p1eUaYVI7sZlc0BHb6OanbY634XbOG9yk/nd9ZVarZs1V+WuXxxiZ/LdIU7gQvfhWbIst2jNVwHJVsUDs09z0Y0lCc+ZSjKi8bvQw62Pu6JnQgYQKKyeIzAm9osxZsNfeWliDG+OHe3a8a+wBBYkz9BtKM8yPTMdy5h89FvCOhtMJpGlMNqAm/DXhlGJzWAZW2yQOmzioPknQll4wiDfIQxbPUzDHC9ed7dBLA2el86AcYt4+z5x5iAaNgcMx8MNtf/0U0UkX4rwi/wuUp19O1gn6VGCF9uzF90Or1Uip2GX21rosmTaNmwj/Y09vdl0lAWemqiT1VcCAwEAAQ==';

    // 连连公钥
    private static $lianLianPublicKey = "-----BEGIN PUBLIC KEY-----" . "\n" .
    "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCSS/DiwdCf/aZsxxcacDnooGph3d2JOj5GXWi+q3gznZauZjkNP8SKl3J2liP0O6rU/Y/29+IUe+GTMhMOFJuZm1htAtKiu5ekW0GlBMWxf4FPkYlQkPE0FtaoMP3gYfh+OwI+fIRrpW3ySn3mScnc6Z700nU/VYrRkfcSCbSnRwIDAQAB" . "\n" .
    "-----END PUBLIC KEY-----";


    /**
     * 连连请求数据
     * @param string $runwaterNum 流水单号
     * @param float $money 充值金额
     * @return array
     */
    public static function lianlianRequestData($runwaterNum, $money): array
    {
        $data = [
            'version'      => '1.1', // 版本号
            'oid_partner'  => self::$oid_partner, // 用户所属商户号
            'user_id'      => JWTAuth::user()->uid, // 用户名
            'timestamp'    => date('YmdHid'), // 时间戳
            'sign_type'    => 'RSA', // 签名方式
            'busi_partner' => '101001', // 商户业务类型
            'no_order'     => $runwaterNum, // 商户唯一订单
            'dt_order'     => date('YmdHid'), // 商户订单时间
            'name_goods'   => '连连充值', // 商品名称
            'money_order'  => $money, // 交易金额
            'notify_url'   => env('LIANLIAN_PAY_NOTIFY_URL'), // 服务器异步通知 地址
            'url_return'   => env('LIANLIAN_PAY_URL_RETURN'), // 支付结束回显 url
            'userreq_ip'   => Request::getClientIp(), // 用户端申请IP
            'risk_item'    => json_encode([ // 风险控制参数
                'frms_ware_category '      => '1008', // 商品类目传1008
                'goods_name'               => '用户资金充值', // 商品名称
                'user_info_mercht_userno ' => JWTAuth::user()->uid, // 你们平台的用户id
                'user_info_dt_register '   => date('YmdHis', strtotime(JWTAuth::user()->created_at)),  // 用户在你们平台的注册时间 格式例子：20180103115612
                'user_info_bind_phone '    => JWTAuth::user()->phone,  // 用户在你们平台的注册手机号
            ]),
        ];

        $data['sign'] = self::RSAsign($data, self::$priKey); // 签名

        return $data;
    }

    /**
     * RSA签名
     * @param array $data 签名数据(需要先排序，然后拼接)
     *  签名用商户私钥，必须是没有经过pkcs8转换的私钥
     *  最后的签名，需要用base64编码
     * @param string $priKey 私钥
     * @return string Sign签名
     */
    protected static function RSAsign($data, $priKey)
    {
        // 排序
        ksort($data);
        // 转换为openssl密钥，必须是没有经过pkcs8转换的私钥
        $res = openssl_get_privatekey($priKey);
        // 调用openssl内置签名方法，生成签名$sign
        openssl_sign(KVstring($data), $sign, $res, OPENSSL_ALGO_MD5);
        // 释放资源
        openssl_free_key($res);
        // base64编码
        $sign = base64_encode($sign);

        return $sign;
    }

    /**
     * RSA验签
     * @param array $data 待签名数据(需要先排序，然后拼接)
     *  1.验签用连连支付公钥
     */
    public static function RSAverify($data)
    {
        // 需要验签的签名,需要base64_decode解码
        $sign = base64_decode($data['sign']);
        unset($data['sign']);
        // 排序
        ksort($data);
        //转换为openssl格式密钥
        $res = openssl_get_publickey(self::$lianLianPublicKey);
        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify(KVstring($data), $sign, $res, OPENSSL_ALGO_MD5);
        //释放资源
        openssl_free_key($res);
        //返回资源是否成功
        if (!$result)
            throw new Exception('连连回调验签失败 ', $data);
    }

    /**
     * 连连回调操作
     * @param array $data 回调数据
     * @throws \Throwable
     */
    public static function backOP($data)
    {
        Log::notice('连连回调参数', $data);
        $uid = null;

        DB::transaction(function () use ($data, &$uid) {
            try {
                // 检查流水是否存在
                $runWater = Runwater::checkHas($data['no_order']);
                $uid      = $runWater->to_uid;
                // 检测是否为重复回调
                Runwater::checkMoreBack($data['oid_paybill']);
                // 金额比对
                if ($runWater->money != $data['money_order']) throw new Exception('回调金额异常');
                // 校验修改校验锁
                Wallet::checkChangLock($uid);
                // 充值成功流水修改
                Runwater::rechargeBackSuccessUpdate(
                    $data['no_order'],
                    $data['dt_order'],
                    $data['oid_paybill'],
                    $data['money_order']);
                // 用户资金增加
                Wallet::updateWallet($uid, $data['money_order'], Wallet::UP_OR_DOWN['增加']);
            } catch (Exception $e) {
                throw new Exception('连连回调失败 ' . $e->getMessage());
            }
        });

        Log::info('用户' . User::whereUid($uid)->value('nickname') . '充值' . $data['money_order'] . '元');
    }
}