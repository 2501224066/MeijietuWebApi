<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Emadadly\LaravelUuid\Uuids;
use Hash;
use Mockery\Exception;

class User  extends Authenticatable implements JWTSubject 
{
    use Notifiable, Uuids;

    protected $table = 'user';

    protected $primaryKey = 'uid';

    public $incrementing = false;

    public $fillable = ['uid','phone','email','password','nickname','ip'];

    //JWTauth
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    //JWTauth
    public function getJWTCustomClaims()
    {
        return [];
    }

    //添加用户
    public static function add($phone, $email, $password, $nickname, $ip)
    {
        $re = self::create([
            'phone' => htmlspecialchars($phone),
            'email' => htmlspecialchars($email),
            'password' => Hash::make(htmlspecialchars($password)),
            'nickname' => htmlspecialchars($nickname),
            'ip' => $ip
        ]);
        if(! $re)
            throw new Exception('注册失败');

        return $re;
    }

    //验证账号密码
    public static function checkLogin($phone, $password)
    {
        $user = self::wherePhone($phone)->first();
        if( ! Hash::check($password, $user->password) ) {
            throw new Exception('账号密码错误');
        }

        return $user;
    }

    //修改信息
    public static function saveInfo($phone ,$field, $value)
    {
        $re = self::wherePhone($phone)->update([
            $field => $value
        ]);
        if( ! $re )
            throw new Exception('保存失败');

        return true;
    }


}