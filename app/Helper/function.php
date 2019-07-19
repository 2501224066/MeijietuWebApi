<?php

/**
 * 生成用户编号
 * @return string
 */
function createUserNum() : string
{
    $asciiArr = [48,49,50,51,52,53,54,55,57,57,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90];
    $randStr = mt_rand(10,35).date('Y'). mt_rand(10,35).date('m').mt_rand(10,35).date('dH').mt_rand(10,35).mt_rand(10,35);
    $arr = str_split($randStr, 2);
    $num = "";
    foreach($arr as $v){
        $num .= chr($asciiArr[$v*1]);
    }

    $count = \App\Models\User::whereUserNum($num)->count();
    if($count)
        $num = createUserNum();

    return $num;
}

/**
 * 生成商品编号
 * @param string $abbreviation 业务简写
 * @return string
 */
function createGoodsNnm($abbreviation) : string
{
    $num = date('d') . strtoupper(uniqid()) . date('Y') . mt_rand(1000000, 9999999) . $abbreviation . date('m');
    $count = \App\Models\Nb\Goods::whereGoodsNum($num)->count();
    if($count)
        $num = createGoodsNnm($abbreviation);

    return $num;
}

/**
 * 当天单数
 * @param string $key 键
 * @return int
 */
function todayCount($key) : int
{
    if (!Cache::has($key))
        Cache::put($key, 1, 60 * 24);

    return sprintf("%04d", Cache::get($key));
}

/**
 * 生成订单编号
 * @param string $key 键
 * @return string
 */
function createIndentNnm($key) : string
{
    $todayCount = todayCount($key); // 当天单数
    return substr(date('Ymd'), 2) . (date('H') * 60 * 60 + date('i') * 60 + date('s')) . $todayCount;
}

/**
 * 生成流水单号
 * @param string $key 键
 * @return string
 */
function createRunwaterNum($key) : string
{
    $todayCount = todayCount($key); // 当天单数
    return date('YmdHis') . mt_rand(1000, 9999) . $todayCount;
}

/**
 * 生成钱包修改校验锁
 * @param string $uid 用户ID
 * @param string $avaiable_money 可用资金
 * @param string $time 上次修改时间
 * @return string
 */
function createWalletChangeLock($uid, $avaiable_money, $time) : string
{
    return md5($uid . substr(env('WALLET_SALT'), 13, 28) . substr(env('WALLET_SALT'), 45, 51) . $avaiable_money * 1 . $time);
}

/**
 * 将已有json数组中的参数按照key_1=value_1&key_2=value2的形式进行排列
 * @param array $data json数组
 * @return string
 */
function KVstring($data) : string
{
    $str = '';
    foreach ($data as $k => $v) {
        $str .= $k . '=' . $v . '&';
    }

    return trim($str, '&');
}