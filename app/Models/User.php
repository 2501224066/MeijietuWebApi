<?php

namespace App\Models;

use App\Models\Log\LogLogin;
use App\Models\Log\LogSaveuserinfo;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Emadadly\LaravelUuid\Uuids;
use Hash;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

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
 * @property string|null $head_portrait 头像
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereHeadPortrait($value)
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
    public static function add($phone, $email, $password, $nickname, $identity, $ip)
    {
        // 添加user
        $user =self::create([
            'phone' => htmlspecialchars($phone),
            'email' => htmlspecialchars($email),
            'password' => Hash::make(htmlspecialchars($password)),
            'nickname' => htmlspecialchars($nickname),
            'identity' => htmlspecialchars($identity),
            'ip' => $ip,
            'head_portrait' => SystemSetting::whereSettingName('default_head_portrait')->value('value')
        ]);
        if ( ! $user)
            throw new Exception('注册失败');

        return self::whereId($user->id)->value('uid');
    }

    // 分配客服
    public static function withUsalesman($uid)
    {
        // 检查用户是否已经分配客服
        self::checkUserHasUsalesman($uid);
        // 获取客服
        $salesman_id = Usalesman::getSalesman();
        // 将客服id与用户id存入它们的关联表
        $re = UserUsalesman::create([
            'uid' => $uid,
            'salesman_id' => $salesman_id
        ]);
        if ( ! $re)
            throw new Exception('分配客服失败');

        return true;
    }

    // 检查用户是否已经分配客服
    public static function checkUserHasUsalesman($uid)
    {
        $count = UserUsalesman::whereUid($uid)->count();
        if ($count)
            throw new Exception('已有专属客服');

        return true;
    }

    // 验证密码
    public static function checkPass($phone, $password)
    {
        $user = self::wherePhone($phone)->first();
        if( ! Hash::check($password, $user->password) )
            throw new Exception('账号/密码错误');

        return $user;
    }

    // 修改密码
    public static function savePass($phone , $new_pass)
    {
        $re = self::wherePhone($phone)->update([
            'password' => Hash::make($new_pass)
        ]);
        if( ! $re )
            throw new Exception('保存失败');

        return true;
    }

    // 修改信息
    public static function saveInfo($data)
    {
        $user = JWTAuth::user();
        $old_info = [
            'head_portrait' => $user->head_portrait,
            'nickname' =>  $user->nickname,
            'sex' =>  $user->sex,
            'birth' =>  $user->birth,
            'qq_ID' =>  $user->qq_ID,
            'weixin_ID' =>  $user->weixin_ID,
        ];
        $new_info = [
            'head_portrait' => htmlspecialchars($data->head_portrait),
            'nickname' => htmlspecialchars($data->nickname),
            'sex' => htmlspecialchars($data->sex),
            'birth' => htmlspecialchars($data->birth),
            'qq_ID' => htmlspecialchars($data->qq_ID),
            'weixin_ID' => htmlspecialchars($data->weixin_ID),
        ];

        DB::transaction(function () use ($user, $old_info, $new_info){
            // 修改信息
            $reOne = DB::table('user', $old_info, $new_info)
                ->where('uid', $user->uid)
                ->update($new_info);
            if( ! $reOne )
                throw new Exception('保存失败');

            // 记录
            $reTwo = DB::table('log_saveuserinfo')
                ->insert([
                    'uid' => JWTAuth::user()->uid,
                    'ip' => \Request::getClientIp(),
                    'old_info' => json_encode($old_info),
                    'new_info' => json_encode($new_info),
                    'time_at' => date('Y-m-d H:i:s')
                ]);
            if ( ! $reTwo)
                throw new Exception('保存失败');
        });

        return true;
    }

    // 检查手机号是否为当前用户手机号
    public static function checkUserPhone($phone)
    {
        if ( ! (JWTAuth::user()->phone == $phone) )
            throw new Exception('违法，非自身手机号');

        return true;
    }


    // 修改手机号并记录
    public static function savePhoneAndLog($phone, $new_phone)
    {
        $old_info = [
            'phone' => $phone
        ];
        $new_info = [
            'phone' => $new_phone
        ];

        DB::transaction(function () use ($phone, $old_info, $new_info){
            // 修改手机号
            $reOne = DB::table('user')
                ->where('phone', $phone)
                ->update($new_info);
            if ( ! $reOne)
                throw new Exception('保存失败');

            // 记录
            $reTwo = DB::table('log_saveuserinfo')
                ->insert([
                    'uid' => JWTAuth::user()->uid,
                    'ip' => \Request::getClientIp(),
                    'old_info' => json_encode($old_info),
                    'new_info' => json_encode($new_info),
                    'time_at' => date('Y-m-d H:i:s')
                ]);
            if ( ! $reTwo)
                throw new Exception('保存失败');
        });

        return true;
    }

    // 检查是否已经认证
    public static function checkRealnameStatus()
    {
        $realnameStatus = JWTAuth::user()->realname_status;
        if ($realnameStatus != 0)
            throw new Exception('已有认证');

        return true;
    }
}