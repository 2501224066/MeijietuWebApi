<?php
$api = app('Dingo\Api\Routing\Router');

$api->group(['version' => 'v1'], function ($api) {

    $api->group(['namespace' => '\App\Http\Controllers\Api'], function ($api) {

        // 注册登录
            // 检查手机号
            $api->post('checkPhone', 'AuthController@checkPhone');
            // 注册
            $api->post('register', 'AuthController@register');
            // 账密登录
            $api->post('signIn', 'AuthController@signIn');
            // 动态登录
            $api->post('codeSignIn', 'AuthController@codeSignIn');
            // 重置密码
            $api->post('resetPass', 'AuthController@resetPass');

        // 验证码
            // 获取邮箱验证码
            $api->get('emailVerifCode', 'CaptchaController@emailVerifCode');
            // 获取图形验证码
            $api->get('getImgCode', 'CaptchaController@getImgCode');
            // 验证图形验证码
            $api->get('checkImgCode', 'CaptchaController@checkImgCode');
            // 获取短信验证码
            $api->get('smsVerifCode', 'CaptchaController@smsVerifCode');

        // 商品
            // 获取商品属性
            $api->get('getGoodsAttribute', 'GoodsController@getGoodsAttribute');
            // 搜索商品
            $api->get('selectGoods', 'GoodsController@selectGoods');

        // JWT身份验证
        $api->group(['middleware' => ['jwt.auth']], function ($api) {
            // 刷新token
            $api->post('refresh', 'AuthController@refresh');
            // 退出登录
            $api->post('signOut', 'AuthController@signOut');

            // 客服
                // 用户专属客服信息
                $api->post('usalsesmanInfo', 'UsalesmanController@usalsesmanInfo');
                // 分配客服
                $api->post('distributionUsalsesman', 'UsalesmanController@distributionUsalsesman');

            // 个人信息
                // 个人实名认证
                $api->post('realnamePeople', 'UserInfoController@realnamePeople');
                // 获取个人实名认证信息
                $api->post('realnamePeopleInfo', 'UserInfoController@realnamePeopleInfo');
                // 企业实名认证
                $api->post('realnameEnterprise', 'UserInfoController@realnameEnterprise');
                // 获取企业实名认证信息
                $api->post('realnameEnterpriseInfo', 'UserInfoController@realnameEnterpriseInfo');
                // 获取用户信息
                $api->post('me', 'AuthController@me');
                // 修改用户信息
                $api->post('saveInfo', 'UserInfoController@saveInfo');
                // 修改手机号
                $api->post('savePhone', 'UserInfoController@savePhone');
                // 修改密码
                $api->post('savePass', 'UserInfoController@savePass');

            // 图片上传
            $api->post('uploadImg', 'FileController@uploadImg');

            // 商品
                // 创建商品
                $api->post('createGoods', 'GoodsController@createGoods');
                // 个人所有商品
                $api->post('goodsBelongToUser', 'GoodsController@goodsBelongToUser');



        });
    });
});

