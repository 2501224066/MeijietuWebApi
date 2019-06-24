<?php

/**
 * 类型管理与调用
 * @param string $parm 类型名称
 * @return array
 */
function type($parm)
{
    switch ($parm) {
        // 请求验证码类型
        case "CODE_TYPE":
            $type = [
                'checkPhone'         => '检查手机号',
                'nextToken'          => '下一步令牌',
                'codeSignIn'         => '动态登录',
                'resetPassCode'      => '重置密码',
                'realnamePeople'     => '个人实名认证',
                'realnameEnterprise' => '企业实名认证',
                'savePhone'          => '修改手机号',
                'savePass'           => '修改密码'
            ];
            break;

        // 上传类型
        case "UPLOAD_TYPE":
            $type = [
                "ID_card"          => '身份证',
                "business_license" => '营业执照',
                "head_portrait"    => '头像',
            ];
            break;
    }

    return $type;
}

/**
 * 生成商品编号
 * @param string $abbreviation 业务简写
 * @return string
 */
function createGoodsNnm($abbreviation)
{
    return date('d') . strtoupper(uniqid()) . date('Y') . mt_rand(1000000, 9999999) . $abbreviation . date('m');
}

// 当天单数
function todayCount($key)
{
    if (!Cache::has($key))
        Cache::put($key, 1, 60 * 24);

    return sprintf("%04d", Cache::get($key));
}

/**
 * 生成订单编号
 * @return string
 */
function createIndentNnm($key)
{
    $todayCount = todayCount($key); // 当天单数
    return substr(date('Ymd'), 2) . (date('H') * 60 * 60 + date('i') * 60 + date('s')) . $todayCount;
}

/**
 * 生成流水单号
 */
function createRunwaterNum($key)
{
    $todayCount = todayCount($key); // 当天单数
    return date('YmdHis') . mt_rand(1000,9999). $todayCount;
}

/**
 * 生成钱包修改校验锁
 */
function createWalletChangLock($uid, $avaiable_money, $time)
{
    return md5($uid . substr(env('WALLET_SALT'), 13, 28) . substr(env('WALLET_SALT'), 45, 51) . $avaiable_money . $time);
}

/**
 * 将已有json数组中的参数按照key_1=value_1&key_2=value2的形式进行排列
 */
function KVstring($data)
{
    $str = '';
    foreach ($data as $k => $v) {
        $str .= $k . '=' . $v . '&';
    }

    return trim('&', $str);
}