# 使用Laravel5.5构建前后分离项目基础配置

## 已安装扩展

1. **predis/predis**  
PHP连接Redis的操作库扩展    
<https://packagist.org/packages/predis/predis>  
    >"predis/predis": "^1.1"  

2. **barryvdh/laravel-ide-helper**  
laravel官方推荐开发扩展  
<https://packagist.org/packages/barryvdh/laravel-ide-helper>  
    > "barryvdh/laravel-ide-helper": "^2.6"  

3. **doctrine/dbal**  
用于帮助laravel-ide-helper为Models注释 
<https://packagist.org/packages/doctrine/dbal> 
    > "doctrine/dbal": " ~2.3"  

4. **tymon/jwt-auth**  
前后分离时身份认证方案  
<https://learnku.com/articles/10885/full-use-of-jwt>  
<https://packagist.org/packages/tymon/jwt-auth>
    > "tymon/jwt-auth": "1.*@rc"

5. **emadadly/laravel-uuid**  
数据库唯一ID扩展  
<https://www.jianshu.com/p/fc332e999911>  
<https://packagist.org/packages/emadadly/laravel-uuid>  
    > "emadadly/laravel-uuid": "^1.2"

6. **dingo/api**  
dingoApi扩展，用于接管api，统一返回json数据  
<https://learnku.com/docs/dingo-api/2.0.0>  
<https://packagist.org/packages/dingo/api>
    > "dingo/api": "2.0.0-alpha1"

7. **barryvdh/laravel-cors**  
laravel跨域方案  
<https://packagist.org/packages/barryvdh/laravel-cors>
    > "barryvdh/laravel-cors": "^0.11.3"

8. **guzzlehttp/guzzle**  
用于发送 HTTP 请求, 与 web 服务集成  
<https://packagist.org/packages/guzzlehttp/guzzle>
    >"guzzlehttp/guzzle": "^6.3"

9. **mews/captcha**
图片验证扩展  
<https://packagist.org/packages/mews/captcha>
    >"gregwar/captcha": "1.*"

## 删除扩展
1. **phpunit/phpunit**  
PHP测试扩展


## 注意事项
1. 请 `pull` 代码后执行 `composer update` 更新/安装扩展包
2. 已将项目 `session` 与 `cache` 数据存储驱动更换为`redis`    
3. 此项目已为扩展做好相关基础配置  
请按各扩展相关文档修改 `.env` 文件内 `****` 位置参数并调试:  
    >`php artisan key:generate`  生成 `APP_KEY`  

    >`php artisan jwt:secret` 生成 `JWT_SECRET`
