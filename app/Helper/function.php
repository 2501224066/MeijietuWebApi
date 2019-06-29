<?php

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
    return date('YmdHis') . mt_rand(1000, 9999) . $todayCount;
}

/**
 * 生成钱包修改校验锁
 */
function createWalletChangeLock($uid, $avaiable_money, $time)
{
    return md5($uid . substr(env('WALLET_SALT'), 13, 28) . substr(env('WALLET_SALT'), 45, 51) . $avaiable_money * 1 . $time);
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

    return trim($str, '&');
}