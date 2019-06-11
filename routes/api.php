<?php
$api = app('Dingo\Api\Routing\Router');

$api->group(['version' => 'v1'], function ($api) {

    $api->group(['namespace' => '\App\Http\Controllers\Api'], function ($api) {

        // 注册登录
        $api->post('checkPhone', 'AuthController@checkPhone'); // 检查手机号
        $api->post('register', 'AuthController@register');     // 注册
        $api->post('signIn', 'AuthController@signIn');         // 账密登录
        $api->post('codeSignIn', 'AuthController@codeSignIn'); // 动态登录
        $api->post('resetPass', 'AuthController@resetPass');   // 重置密码

        // 验证码
        $api->get('emailVerifCode', 'CaptchaController@emailVerifCode'); // 获取邮箱验证码
        $api->get('getImgCode', 'CaptchaController@getImgCode');         // 获取图形验证码
        $api->get('checkImgCode', 'CaptchaController@checkImgCode');     // 验证图形验证码
        $api->get('smsVerifCode', 'CaptchaController@smsVerifCode');     // 获取短信验证码

        // 商品属性
        $api->get('weixinGoodsAttribute', 'GoodsAttributeController@weixinGoodsAttribute');           // 微信商品属性
        $api->get('weiboGoodsAttribute', 'GoodsAttributeController@weiboGoodsAttribute');             // 微博商品属性
        $api->get('videoGoodsAttribute', 'GoodsAttributeController@videoGoodsAttribute');             // 视频商品属性
        $api->get('selfmediaGoodsAttribute', 'GoodsAttributeController@selfmediaGoodsAttribute');     // 自媒体商品属性
        $api->get('softarticleGoodsAttribute', 'GoodsAttributeController@softarticleGoodsAttribute'); // 软文商品属性
        $api->get('currencyGoodsAttribute', 'GoodsAttributeController@currencyGoodsAttribute');       // 公共商品属性

        // 搜索商品
        $api->get('selectWeixinGoods', 'SelectGoodsController@selectWeixinGoods');           // 搜索微信商品
        $api->get('selectWeiboGoods', 'SelectGoodsController@selectWeiboGoods');             // 搜索微博商品
        $api->get('selectVideoGoods', 'SelectGoodsController@selectVideoGoods');             // 搜索视频商品
        $api->get('selectSelfmediaGoods', 'SelectGoodsController@selectSelfmediaGoods');     // 搜索自媒体商品
        $api->get('selectSoftarticleGoods', 'SelectGoodsController@selectSoftarticleGoods'); // 搜索软文商品

        // JWT身份验证
        $api->group(['middleware' => ['jwt.auth']], function ($api) {
            $api->post('refresh', 'AuthController@refresh'); // 刷新token
            $api->post('signOut', 'AuthController@signOut'); // 退出登录

            // 客服
            $api->post('usalsesmanInfo', 'UsalesmanController@usalsesmanInfo');                 // 用户专属客服信息
            $api->post('distributionUsalsesman', 'UsalesmanController@distributionUsalsesman'); // 分配客服

            // 个人信息
            $api->post('realnamePeople', 'UserInfoController@realnamePeople');                 // 个人实名认证
            $api->post('realnamePeopleInfo', 'UserInfoController@realnamePeopleInfo');         // 获取个人实名认证信息
            $api->post('realnameEnterprise', 'UserInfoController@realnameEnterprise');         // 企业实名认证
            $api->post('realnameEnterpriseInfo', 'UserInfoController@realnameEnterpriseInfo'); // 获取企业实名认证信息
            $api->post('me', 'AuthController@me');                                             // 获取用户信息
            $api->post('saveInfo', 'UserInfoController@saveInfo');                             // 修改用户信息
            $api->post('savePhone', 'UserInfoController@savePhone');                           // 修改手机号
            $api->post('savePass', 'UserInfoController@savePass');                             // 修改密码

            // 文件处理
            $api->post('uploadImg', 'FileController@uploadImg'); // 图片上传

            // 创建商品
            $api->post('createWeixinGoods', 'CreateGoodsController@createWeixinGoods');           // 创建微信商品
            $api->post('createWeiboGoods', 'CreateGoodsController@createWeiboGoods');             // 创建微博商品
            $api->post('createVideoGoods', 'CreateGoodsController@createVideoGoods');             // 创建视频商品
            $api->post('createSelfmediaGoods', 'CreateGoodsController@createSelfmediaGoods');     // 创建自媒体商品
            $api->post('createSoftarticleGoods', 'CreateGoodsController@createSoftarticleGoods'); // 创建软文商品

            // 搜索用户创建的全部商品
            $api->post('userGoods', 'SelectGoodsController@userGoods');

            // 商品收藏
            $api->post('collectionGoods', 'UserCollectionController@collectionGoods'); // 收藏商品
            $api->post('delCollection/{id}', 'UserCollectionController@delCollection');     // 删除收藏
            $api->post('getCollection', 'UserCollectionController@getCollection');     // 获取收藏

            // 购物车
            $api->post('joinShopcart', 'UserShopcartController@joinShopcart');    // 加入购物车
            $api->post('shopcartDel/{id}', 'UserShopcartController@shopcartDel'); // 从购物车删除
            $api->post('getShopcart', 'UserShopcartController@getShopcart');      // 购物车数据
        });
    });
});

