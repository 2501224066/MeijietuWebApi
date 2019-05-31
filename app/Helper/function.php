<?php

// 类型管理与调用
function type($parm){
    // 请求验证码类型
    $CODE_TYPE = [
        'checkPhone' => '检查手机号',
        'nextToken' => '下一步令牌',
        'codeSignIn' => '动态登录',
        'resetPassCode' => '重置密码',
        'realnamePeople' => '个人实名认证',
        'realnameEnterprise' => '企业实名认证',
        'savePhone' => '修改手机号',
        'savePass' => '修改密码'
    ];

    // 上传类型
    $UPLOAD_TYPE = [
        "ID_card" => '身份证',
        "business_license" => '营业执照',
        "head_portrait" => '头像',
    ];

    switch ($parm){
        case "CODE_TYPE":
            $type = $CODE_TYPE;
            break;

        case "UPLOAD_TYPE":
            $type = $UPLOAD_TYPE;
            break;
    }

    return $type; //返回类型数据
}

// 生存商品编号
function createGoodsNnm()
{
    return date('d'). strtoupper(uniqid()). date('Y').mt_rand(1000000, 9999999).date('m');
}