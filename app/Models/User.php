<?php

namespace App\Models;

use App\Models\Up\Wallet;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\User
 *
 * @property int $uid 用户id （初始值1000000）
 * @property string $user_num 用户编号
 * @property string $nickname 昵称
 * @property string $email 邮箱
 * @property string $phone 电话
 * @property string $password 密码
 * @property string|null $head_portrait 头像
 * @property int|null $sex 性别 1=男 0=女
 * @property string|null $birth 出生日期
 * @property string|null $qq_ID qq号
 * @property string|null $weixin_ID 微信号
 * @property int|null $identity 身份 1=广告主 2=媒体主 3=业务员
 * @property int $realname_status 实名认证状态 0=未认证 1=个人认证 2=企业认证
 * @property string $ip 客户端最近一次登录ip
 * @property int|null $status 状态 0=禁用 1=启用
 * @property int|null $salesman_id 客服id
 * @property string|null $salesman_name 客服名称
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\Up\Wallet $wallet
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereHeadPortrait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereQqID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRealnameStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereSalesmanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereSalesmanName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUserNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWeixinID($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'user';

    protected $primaryKey = 'uid';

    public $incrementing = false;

    public $guarded = [];

    // 官方卖家
    const GF_SELLER = 1;

    // 官方买家
    const GF_BUYER = 2;

    const REALNAME_STATUS = [
        '未认证'  => 0,
        '个人认证' => 1,
        '企业认证' => 2
    ];

    const IDENTIDY = [
        '广告主' => 1,
        '媒体主' => 2,
        '业务员' => 3
    ];

    const STATUS = [
        '启用' => 1,
        '禁用' => 0
    ];

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

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'uid', 'uid');
    }

    // 首页客服
    public static function indexPageSalesman()
    {
        $salesman = self::whereIdentity(User::IDENTIDY['业务员'])->where('status', User::STATUS['启用'])->inRandomOrder()->first();
        return [
            'salesman_id'            => $salesman->uid,
            'salesman_qq_ID'         => $salesman->qq_ID,
            'salesman_weixin_ID'     => $salesman->weixin_ID,
            'salesman_name'          => $salesman->nickname,
            'salesman_head_portrait' => $salesman->head_portrait
        ];
    }

    // 添加用户
    public static function add($request)
    {
        // 添加user
        $time = date('Y-m-d H:i:s');
        $uid  = self::insertGetId([
            'user_num'      => createNum('USER'),
            'phone'         => htmlspecialchars($request->phone),
            'email'         => htmlspecialchars($request->email),
            'password'      => Hash::make(htmlspecialchars($request->password)),
            'nickname'      => htmlspecialchars($request->nickname),
            'identity'      => htmlspecialchars($request->identity),
            'ip'            => $request->getClientIp(),
            'head_portrait' => SystemSetting::whereSettingName('default_head_portrait')->value('img'),
            'salesman_id'   => $request->salesman_id,
            'salesman_name' => $request->salesman_id ? User::whereUid($request->salesman_id)->value('nickname') : null,
            'created_at'    => $time,
            'updated_at'    => $time

        ]);
        if (!$uid)
            throw new Exception('注册失败');

        return $uid;
    }

    // 验证密码
    public static function checkPass($phone, $password)
    {
        $user = self::wherePhone($phone)->first();
        if (!Hash::check($password, $user->password))
            throw new Exception('账号/密码错误');

        return $user;
    }

    // 修改密码
    public static function savePass($phone, $new_pass)
    {
        $re = self::wherePhone($phone)->update([
            'password' => Hash::make($new_pass)
        ]);
        if (!$re)
            throw new Exception('保存失败');

        return true;
    }

    // 修改信息
    public static function saveInfo($data)
    {
        $user     = JWTAuth::user();
        $old_info = [
            'head_portrait' => $user->head_portrait,
            'nickname'      => $user->nickname,
            'sex'           => $user->sex,
            'birth'         => $user->birth,
            'qq_ID'         => $user->qq_ID,
            'weixin_ID'     => $user->weixin_ID,
        ];
        $new_info = [
            'head_portrait' => htmlspecialchars($data->head_portrait),
            'nickname'      => htmlspecialchars($data->nickname),
            'sex'           => htmlspecialchars($data->sex),
            'birth'         => htmlspecialchars($data->birth),
            'qq_ID'         => htmlspecialchars($data->qq_ID),
            'weixin_ID'     => htmlspecialchars($data->weixin_ID),
        ];

        DB::transaction(function () use ($user, $old_info, $new_info) {
            try {
                // 修改信息
                DB::table('user', $old_info, $new_info)
                    ->where('uid', $user->uid)
                    ->update($new_info);

                // 记录
                DB::table('log_saveuserinfo')
                    ->insert([
                        'uid'      => JWTAuth::user()->uid,
                        'ip'       => \Request::getClientIp(),
                        'old_info' => json_encode($old_info),
                        'new_info' => json_encode($new_info),
                        'time_at'  => date('Y-m-d H:i:s')
                    ]);

            } catch (\Exception $e) {
                throw new Exception('保存失败');
            }
        });

        return true;
    }

    // 检查手机号是否为当前用户手机号
    public static function checkUserPhone($phone)
    {
        if (!(JWTAuth::user()->phone == $phone))
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

        DB::transaction(function () use ($phone, $old_info, $new_info) {
            try {
                // 修改手机号
                DB::table('user')
                    ->where('phone', $phone)
                    ->update($new_info);

                // 记录
                DB::table('log_saveuserinfo')
                    ->insert([
                        'uid'      => JWTAuth::user()->uid,
                        'ip'       => \Request::getClientIp(),
                        'old_info' => json_encode($old_info),
                        'new_info' => json_encode($new_info),
                        'time_at'  => date('Y-m-d H:i:s')
                    ]);
            } catch (\Exception $e) {
                throw new Exception('保存失败');
            }
        });

        return true;
    }

    // 检查实名认证
    public static function checkRealnameStatus($realnameStatus, $io)
    {
        switch ($io) {
            case 'y':
                if ($realnameStatus != self::REALNAME_STATUS['未认证'])
                    throw new Exception('已有认证');
                break;

            case 'n':
                if ($realnameStatus == self::REALNAME_STATUS['未认证'])
                    throw new Exception('未实名认证');
                break;

        }
        return true;
    }

    // 检查身份
    public static function checkIdentity($trueIdentity)
    {
        $identity = JWTAuth::user()->identity;
        if ($identity != $trueIdentity)
            throw new Exception('此功能您未拥有');

        return true;
    }
}