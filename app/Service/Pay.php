<?php


namespace App\Service;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class Pay
{
    // 商户号
    private static $oid_partner = '201906120002511007';

    // 商户管理员手机号
    private static $phone = '3044889256';

    // 私钥
    private static $priKey = '-----BEGINRSAPRIVATEKEY-----MIICXAIBAAKBgQDzELgJKj9SMGPXRYHO2rVjIsIlxNApZRxWJKQ3RQqaKaGs93v2owmeJVOsSbXCf7NLXED1+fEqY3xv4YWzYdAEOenGbS2iqbst7H/2FvJOrewMniwgdssiNRAi+eCmZlLiniWAjpAjw+Ai9MnsEHAxDap88QfJ533eycWS5xp45QIDAQABAoGAN2jsS0qSPM5DNGPn/5vkFcFquOlw+r7OAuU/ekoG7LXo4WFZpRPtsVuZA4GaKciqutdBB9H+pEchqu+iZw45OQuqzzN2G+f6w1aJf2N7xqTlAnZIdJvJvkmH/cYkw3T87vRG5g95bqxMYDpez3JBbPJiZ1e6dpQhL/7UgJg0wNECQQD95/y7QXqYAhI1l/pxQ4xVH7S6aS0kULALnCeUQWycmDFH4YKiyqGiZ295V+Q7jjo8TdJbI5AJS0/c7jFDuHaDAkEA9RHYh4qzyccruxKmo8cfjQhCT7tchzblzYAxtpgAY6pP/P5iBLqkXxsiRAMrP7kPFaPIOicmodEo3hi17ml2dwJBAPplaTlmRrdX+4s8+O/wNJnSLdJUXP9eT27zrZiouKrp8Fe6DrHqcWKO7UFWqy8MgWPtP1FADhEMY5M2mAD4Dm8CQHtocys+E28mlsTrjXKn0SGJ6SqRZPTKFkq3pVEXlgqaNxFlYCKVgjRKS6UIG31JSWlSQn/WO0P9OaEtvF/ER90CQBdvkeSXkWFNTniAiUAh73lA/DHN4O2rbiSeFIQsqBBrNOnSK5Mz6V98dL5m8LGOKv0DVbvTdAjFhrlzXuVO5AE=-----ENDRSAPRIVATEKEY-----';

    // 公钥
    private static $publicKey = '-----BEGINPUBLICKEY-----MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDzELgJKj9SMGPXRYHO2rVjIsIlxNApZRxWJKQ3RQqaKaGs93v2owmeJVOsSbXCf7NLXED1+fEqY3xv4YWzYdAEOenGbS2iqbst7H/2FvJOrewMniwgdssiNRAi+eCmZlLiniWAjpAjw+Ai9MnsEHAxDap88QfJ533eycWS5xp45QIDAQAB-----ENDPUBLICKEY-----';


    /**
     * 连连请求数据
     * @param string $runwaterNum 流水单号
     * @param float $money 充值金额
     * @return array
     */
    public static function lianlianRequestData($runwaterNum, $money)
    {
        $data = [
            'version'      => '1.1', // 版本号
            'oid_partner'  => self::$oid_partner, // 用户所属商户号
            'user_id'      => JWTAuth::user()->uid, // 用户名
            'timetamp'     => date('YmdHid'), // 时间戳
            'sign_type'    => 'RSA', // 签名方式
            'busi_partner' => '101001', // 商户业务类型
            'no_order'     => $runwaterNum, // 商户唯一订单
            'dt_orcer'     => date('YmdHid'), // 商户订单时间
            'name_goods'   => '用户资金充值', // 商品名称
            'money_order'  => $money, // 交易金额
            'notify_url'   => env('PAY_NOTIFY_URL'), // 服务器异步通知 地址
            'url_return'   => env('PAY_URL_RETURN'), // 支付结束回显 url
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
     * $data签名数据(需要先排序，然后拼接)
     * 签名用商户私钥，必须是没有经过pkcs8转换的私钥
     * 最后的签名，需要用base64编码
     * @param $data
     * @param $priKey
     * @return string Sign签名
     */
    protected static function RSAsign($data, $priKey)
    {
        // 排序
        ksort($data);
        // 转换为openssl密钥，必须是没有经过pkcs8转换的私钥
        $res = openssl_get_privatekey($priKey);
        // 调用openssl内置签名方法，生成签名$sign
        openssl_sign($data, $sign, $res, OPENSSL_ALGO_MD5);
        // 释放资源
        openssl_free_key($res);
        // base64编码
        $sign = base64_encode($sign);
        // 日志记录
        Log::info("签名原串:" . $data . "\n");

        return $sign;
    }


    /**
     * RSA验签
     * $data待签名数据(需要先排序，然后拼接)
     * $sign需要验签的签名,需要base64_decode解码
     * 验签用连连支付公钥
     * return 验签是否通过 bool值
     */
    function Rsaverify($data, $sign)  {
        //读取连连支付公钥文件
        $pubKey = file_get_contents('key/llpay_public_key.pem');

        //转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);

        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($data, base64_decode($sign), $res,OPENSSL_ALGO_MD5);

        //释放资源
        openssl_free_key($res);

        //返回资源是否成功
        return $result;
    }
}