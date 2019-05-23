<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Emadadly\LaravelUuid\Uuids;
use Hash;
use Mockery\Exception;

/**
 * App\Models\User
 *
 * @property string $id UUID
 * @property int $uid 用户id
 * @property string $nickname 昵称
 * @property string $email 邮箱
 * @property string $phone 电话
 * @property string $password 密码
 * @property int|null $sex 性别 1=男 2=女
 * @property string|null $birth 出生日期
 * @property string|null $qq_ID qq号
 * @property string|null $weixin_ID 微信号
 * @property int|null $identity 身份 1=广告主 2=流量主 3=代理
 * @property int $realname_status 实名认证状态 0=未认证 1=个人认证 2=企业认证
 * @property string $ip 客户端最近一次登录ip
 * @property int|null $status 状态 0=禁用 1=启用
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User uuid($uuid, $first = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereQqID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRealnameStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWeixinID($value)
 * @mixin \Eloquent
 */
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