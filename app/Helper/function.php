<?php

// 类型管理与调用
function type($parm){
    // 请求验证码类型
    $CODE_TYPE = [
        'checkPhone' => '检查手机号',
        'nextToken' => '下一步令牌',
        'codeSignIn' => '动态登录',
        'resetPassCode' => '重置密码',
    ];

    // 上传类型
    $UPLOAD_TYPE = [];


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