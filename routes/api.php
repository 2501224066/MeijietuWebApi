<?php
$api = app('Dingo\Api\Routing\Router');

$api->group(['version' => 'v1'], function ($api) {

    $api->group(['namespace' => '\App\Http\Controllers\Api'], function ($api) {
        
        //注册登录
        $api->post('checkPhone', 'AuthController@checkPhone');  // 检查手机号
        $api->post('register', 'AuthController@register');      // 注册
        $api->post('signIn', 'AuthController@signIn');          // 账密登录
        $api->post('codeSignIn', 'AuthController@codeSignIn');  // 动态登录
        $api->post('signOut', 'AuthController@signOut');        // 退出登录
        $api->post('resetPass', 'AuthController@resetPass');    // 重置密码
        $api->post('refresh', 'AuthController@refresh');        // 刷新token
        
        //验证码
        $api->get('emailVerifCode', 'CaptchaController@emailVerifCode');    // 获取邮箱验证码
        $api->get('getImgCode', 'CaptchaController@getImgCode');            // 获取图形验证码
        $api->get('checkImgCode', 'CaptchaController@checkImgCode');        // 验证图形验证码
        $api->get('smsVerifCode', 'CaptchaController@smsVerifCode');        // 获取短信验证码
    
        $api->group(['middleware' => ['jwt.auth']], function ($api) {
            
        });
    });
});

