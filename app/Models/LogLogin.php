<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LogLogin
 *
 * @property int $log_login_id
 * @property int $uid
 * @property string $user_phone 用户手机号
 * @property string $ip 客户端IP
 * @property string $agent 设备信息
 * @property int $login_type 登录方式 1=账密登录 2=动态登录
 * @property int $login_status 状态 0=失败 1=失败
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogLogin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogLogin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogLogin query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogLogin whereAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogLogin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogLogin whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogLogin whereLogLoginId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogLogin whereLoginStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogLogin whereLoginType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogLogin whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogLogin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogLogin whereUserPhone($value)
 * @mixin \Eloquent
 */
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