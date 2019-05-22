<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LogLogin extends Model
{
    protected $table = 'log_login';

    protected $primaryKey = 'log_login_id';

    protected $guarded = [];

    const LOGIN_TYPE = [
        "1" => "账密登录",
        "2" => "动态登录"
    ];

    public static function write($phone, $loginType)
    {
        $user = User::wherePhone($phone)->first();
        $ip = \Request::getClientIp();

        $re = self::create([
            "uid" => $user->uid,
            "user_phone" => $user->phone,
            "ip" => $ip,
            "agent" => strtolower($_SERVER['HTTP_USER_AGENT']),
            "login_type" => $loginType,
            "login_status" => 0
        ]);

        return $re->log_login_id;
    }
}