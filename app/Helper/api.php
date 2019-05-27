<?php

use Mockery\Exception;

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

    $re = curl_exec($curl);
    if ( ! $re)
        throw new Exception("请求失败");

    return $re;
}

// 证件识别
function getIDcheck($img_base64, $typeId = 2) //1(一代身份证),2(二代身份证正面),3(二代身份证背面),4(临时身份证),5(驾照),6(行驶证),7(军官证),9(中华人民共和国往来港澳通行证 ),10(台湾居民往来大陆通行证),11(大陆居民往来台湾通行证),12(签证),13(护照),14(港澳居民来往内地通行证正面),15(港澳居民来往内地通行证背面),16(户口本),22(卡式港澳通行证),25(新版台湾居民往来大陆通行证正面 ),26(新版台湾居民往来大陆通行证背面 ),101(二代身份证正面背面自动分类),1000(居住证 ),1001(香港永久性居民身份证),1002(登机牌),1003(边民证照片页),1004(边民证个人信息页),1005(澳门身份证),1031(台湾身份证正面),1032(台湾身份证背面)
{
    $host = "http://ocrapi.sinosecu.com.cn";
    $path = "/api/recogliu.do";
    $method = "POST";
    $appcode = env('IDCHECK_APP_CODE');
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
    //根据API的要求，定义相对应的Content-Type
    array_push($headers, "Content-Type".":"."application/x-www-form-urlencoded; charset=UTF-8");
    $querys = "typeId=".$typeId;
    $bodys = "img=".$img_base64;
    $url = $host . $path . "?" . $querys;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    if (1 == strpos("$".$host, "https://")) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);

    $re = curl_exec($curl);
    if ( ! $re)
        throw new Exception("请求失败");

    return $re;
}