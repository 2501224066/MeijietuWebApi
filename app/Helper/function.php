<?php

/**
 * 生成编号
 * @param string $numType 具有标识属性的，组成key的元素
 * @return string
 */
function createNum($numType): string
{
    // 订单数key
    $key = $numType . date('Ymd');
    // 当天单数
    $todayCount = todayCount($key);
    // 单数自增
    Cache::increment($key);

    return substr(date('Ymd'), 2) . mt_rand(1000, 9999) . $todayCount;

}

/**
 * 当天单数
 *  1.容纳量-千万
 * @param string $key 键
 * @return string
 */
function todayCount($key): string
{
    if (!Cache::has($key))
        Cache::put($key, 1, 60 * 24);

    return sprintf("%08d", Cache::get($key));
}

/**
 * 生成钱包修改校验锁
 * @param string $uid 用户ID
 * @param string $avaiable_money 可用资金
 * @param string $time 上次修改时间
 * @return string
 */
function createWalletChangeLock($uid, $avaiable_money, $time): string
{
    return md5($uid . substr(config('services.wallet.salt'), 13, 28) . substr(config('services.wallet.salt'), 45, 51) . $avaiable_money * 1 . $time);
}

/**
 * 将已有json数组中的参数按照key_1=value_1&key_2=value2的形式进行排列
 * @param array $data json数组
 * @return string
 */
function KVstring($data): string
{
    $str = '';
    foreach ($data as $k => $v) {
        $str .= $k . '=' . $v . '&';
    }

    return trim($str, '&');
}