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
 * @property string $id
 * @property int $uid
 * @property string $email 账号
 * @property string $password 密码
 * @property string|null $nickname 昵称
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User uuid($uuid, $first = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
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
    public static function saveInfo($email ,$field, $value)
    {
        $re = self::whereEmail($email)->update([
            $field => $value
        ]);
        if( ! $re )
            throw new Exception('保存失败');

        return true;
    }


}