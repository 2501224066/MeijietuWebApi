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


/**
 * 生成订单编号
 * @return string
 */
function createIndentNnm($key)
{
    $todayCount = \App\Models\Indent\IndentInfo::todayIndentCount($key); // 当天订单数量
    return substr(date('Ymd'), 2) . (date('H') * 60 * 60 + date('i') * 60 + date('s')) . $todayCount;
}