<?php


namespace App\Service;


use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;
use Mockery\Exception;

class Pub
{
    const CODE_TYPE = [
        '检查手机号'  => 'checkPhone',
        '下一步令牌'  => 'nextToken',
        '动态登录'   => 'codeSignIn',
        '重置密码'   => 'resetPassCode',
        '个人实名认证' => 'realnamePeople',
        '企业实名认证' => 'realnameEnterprise',
        '修改手机号'  => 'savePhone',
        '修改密码'   => 'savePass'
    ];

    const UPLOAD_TYPE = [
        '身份证'  => 'ID_card',
        '营业执照' => 'business_license',
        '头像'   => 'head_portrait',
        '订单文档' => 'indent_word'
    ];

    // 检查状态
    public static function checkStatus($status, $needStatus)
    {
        if ($status != $needStatus)
            throw new Exception('状态非法');

        return true;
    }
}