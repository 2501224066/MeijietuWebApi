<?php


namespace App\Service;


use App\Models\SystemSetting;
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
     * 生成流水订单
     */
    // TODO...

    /**
     * 连连请求参数
     */
    public static function requestData($uid, $no_order, $money)
    {
        $data = [
            'version'      => '1.1', // 版本号
            'oid_partner'  => self::$oid_partner, // 用户所属商户号
            'user_id'      => JWTAuth::user()->uid, // 用户名
            'timetamp'     => date('YmdHid'), // 时间戳
            'sign_type'    => 'RSA', // 签名方式
            'busi_partner' => '101001', // 商户业务类型
            'no_order'     => $no_order, // 商户唯一订单
            'dt_orcer'     => date('YmdHid'), // 商户订单时间
            'name_goods'   => '用户资金充值', // 商品名称
            'money_order'  => $money, // 交易金额
            'notify_url'   => env('APP_URL'), // 服务器异步通知 地址
            'url_return'   => env('PAY_URL_RETURN'), // 支付结束回显 url
            'userreq_ip'   => Request::getClientIp(), // 用户端申请IP
            'valid_order'  => SystemSetting::whereSettingName('runwater_indent_life')->value('value') / 60, // 订单有效时间
            'risk_item'    => json_encode([ // 风险控制参数
                'frms_ware_category '      => '1008', // 商品类目传1008
                'goods_name'               => '用户资金充值', // 商品名称
                'user_info_mercht_userno ' => JWTAuth::user()->uid, // 你们平台的用户id
                'user_info_dt_register '   => date('YmdHis', strtotime(JWTAuth::user()->created_at)),  // 用户在你们平台的注册时间 格式例子：20180103115612
                'user_info_bind_phone '    => JWTAuth::user()->phone,  // 用户在你们平台的注册手机号

            ]),
        ];

        $data['sign'] = self::Rsasign($data, self::$priKey); // 签名
    }

    /**RSA签名
     * $data签名数据(需要先排序，然后拼接)
     * 签名用商户私钥，必须是没有经过pkcs8转换的私钥
     * 最后的签名，需要用base64编码
     * return Sign签名
     */
    protected static function Rsasign($data, $priKey)
    {
        //转换为openssl密钥，必须是没有经过pkcs8转换的私钥
        $res = openssl_get_privatekey($priKey);

        //调用openssl内置签名方法，生成签名$sign
        openssl_sign($data, $sign, $res, OPENSSL_ALGO_MD5);

        //释放资源
        openssl_free_key($res);

        //base64编码
        $sign = base64_encode($sign);
        //file_put_contents("log.txt","签名原串:".$data."\n", FILE_APPEND);
        return $sign;
    }

}