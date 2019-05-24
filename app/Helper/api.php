<?php

// 请求银行卡四要素查询接口
function getBankInfo($acct_name, $acct_pan, $cert_id, $phone_num,  $cert_type='01', $needBelongArea=true)
{
    $host = "https://ali-bankcard4.showapi.com";
    $path = "/bank4";
    $method = "GET";
    $appcode = env("BANKINFO_APP_CODE");
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
    $querys = "acct_name=" . $acct_name . "&acct_pan=" . $acct_pan . "&cert_id=" . $cert_id . "&cert_type=" . $cert_type . "&needBelongArea=" . $needBelongArea . "&phone_num=" . $phone_num;
    $url = $host . $path . "?" . $querys;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    if (1 == strpos("$" . $host, "https://")) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }

    return curl_exec($curl);
}