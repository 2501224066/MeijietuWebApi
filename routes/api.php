<?php
$api = app('Dingo\Api\Routing\Router');

$api->group(['version' => 'v1'], function ($api) {

    $api->group(['namespace' => '\App\Http\Controllers\Api'], function ($api) {

        // 充值回调
        $api->post('lianLianPayRechargeBack', 'PayController@lianLianPayRechargeBack');

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

            // 文件处理
                // 图片上传
                $api->post('uploadImg', 'FileController@uploadImg');
                // 文件上传
                $api->post('uploadFile', 'FileController@uploadFile');

            // 商品
                // 创建商品
                $api->post('createGoods', 'GoodsController@createGoods');
                // 获取个人所有商品
                $api->post('goodsBelongToUser', 'GoodsController@goodsBelongToUser');

            // 收藏
                // 收藏商品
                $api->post('collectionGoods', 'CollectionController@collectionGoods');
                // 获取收藏商品
                $api->post('getCollection', 'CollectionController@getCollection');
                // 删除收藏商品
                $api->post('delCollection', 'CollectionController@delCollection');

            // 购物车
                // 加入购物车
                $api->post('joinShopcart', 'ShopcartController@joinShopcart');
                // 获取购物车商品
                $api->post('getShopcart', 'ShopcartController@getShopcart');
                // 删除购物车商品
                $api->post('delShopcart', 'ShopcartController@delShopcart');

            // 订单
                // 生成订单
                $api->post('createIndent', 'IndentController@createIndent');
                // 订单付款
                $api->post('indentPayment', 'IndentController@indentPayment');

            //连连三方
                //充值
                $api->post('recharge', 'PayController@recharge');

            //钱包
                // 生成钱包
                $api->post('createWallet', 'WalletController@createWallet');
                // 钱包信息
                $api->post('walletInfo', 'WalletController@walletInfo');
        });
    });
});

