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

        // 模块类型
        case "MODULAR_TYPE":
            $type = [
                'WEIXIN'      => '微信营销',
                'WEIBO'       => '微博营销',
                'VIDEO'       => '视频营销',
                'SELFMODEL'   => '自媒体营销',
                'SOFTARTICLE' => '软文营销',
            ];
            break;

        // 业务类型
        case "BUSINESS_TYPE":
            $type = [
                'Market' => '10',
            ];
            break;
    }

    return $type;
}

/**
 * 生成商品编号
 * @param string $tag 标记(微信=X, 微博=B, 视频=V, 自媒体=M, 软文=A)
 * @return string
 */
function createGoodsNnm($tag)
{
    return date('d') . strtoupper(uniqid()) . date('Y') . mt_rand(1000000, 9999999) . $tag . date('m');
}


/**
 * 生成订单编号
 * @param int $business 业务类型
 * @return string
 */
function createIndentNnm($business)
{
    $todayCount = \App\Service\ModularData::todayIndentCount(); // 当天订单数量
    return type('BUSINESS_TYPE')[$business] . substr(date('Ymd'), 2) . (date('H') * 60 * 60 + date('i') * 60 + date('s')) . $todayCount;
}


/*use Illuminate\Support\Facades\Cache;
// 当天订单数
public static function todayIndentCount()
{
    $name = 'INDENTCOUNT' . date('Ymd');
    if (!Cache::has($name))
        Cache::put($name, 1, 60 * 24);

    return sprintf("%04d", Cache::get($name));
}*/