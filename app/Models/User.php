<?php

namespace App\Models;

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
 * @property string $nickname 昵称
 * @property string $email 邮箱
 * @property string $phone 电话
 * @property string $password 密码
 * @property string|null $head_portrait 头像
 * @property int|null $sex 性别 1=男 0=女
 * @property string|null $birth 出生日期
 * @property string|null $qq_ID qq号
 * @property string|null $weixin_ID 微信号
 * @property int|null $identity 身份 1=广告主 2=流量主 3=业务员
 * @property int $realname_status 实名认证状态 0=未认证 1=个人认证 2=企业认证
 * @property string $ip 客户端最近一次登录ip
 * @property int|null $status 状态 0=禁用 1=启用
 * @property int|null $salesman_id 客服id
 * @property string|null $salesman_name 客服名称
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
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

    const GF = 1;

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

    //添加用户
    public static function add($request)
    {
        // 添加user
        $user = self::create([
            'phone'         => htmlspecialchars($request->phone),
            'email'         => htmlspecialchars($request->email),
            'password'      => Hash::make(htmlspecialchars($request->password)),
            'nickname'      => htmlspecialchars($request->nickname),
            'identity'      => htmlspecialchars($request->identity),
            'ip'            => $request->getClientIp(),
            'head_portrait' => SystemSetting::whereSettingName('default_head_portrait')->value('img')
        ]);
        if (!$user)
            throw new Exception('注册失败');

        return self::wherePhone($user->phone)->value('uid');
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

    // 分配客服
    public static function withSalesman($uid)
    {
        // 检查用户是否已经分配客服
        self::checkUserHasSalesman($uid);
        // 取得一个客服id
        $salesman_id = self::getSalesman();
        // 将客服id与用户id存入它们的关联表
        $re = User::whereUid($uid)->update([
            'salesman_id'   => $salesman_id,
            'salesman_name' => User::whereUid($salesman_id)->value('nickname')
        ]);
        if (!$re)
            throw new Exception('分配客服失败');

        return true;
    }

    // 检查用户是否已经分配客服
    public static function checkUserHasSalesman($uid)
    {
        $re = User::whereUid($uid)->value('salesman_id');
        if ($re)
            throw new Exception('已有专属客服');

        return true;
    }

    // 循环获取客服id
    public static function getSalesman()
    {
        // 所有客服id
        $salesmanArr = User::whereIdentity(self::IDENTIDY['业务员'])
            ->where('status', self::STATUS['启用'])
            ->pluck('uid');

        // 位置初始值
        $init = 0;

        // 位置不存在设置初始值
        if (!Cache::has('salesman_tag')) {
            Cache::put('salesman_tag', $init, 60);
            return $salesmanArr[$init];
        }

        // 位置超出回归初始值
        if (empty($salesmanArr[Cache::get('salesman_tag') + 1])) {
            Cache::put('salesman_tag', $init, 60);
            return $salesmanArr[$init];
        }

        Cache::put('salesman_tag', Cache::get('salesman_tag') + 1, 60);

        return $salesmanArr[Cache::get('salesman_tag') + 1];
    }

    // 获取客服信息
    public static function salesmanInfo()
    {
        $salesman_id = JWTAuth::user()->salesman_id;
        if (!$salesman_id)
            throw new Exception('未找到客服信息');

        $info = User::whereUid($salesman_id)->first();
        return [
            'salesman_id'            => $info->uid,
            'salesman_qq_ID'         => $info->qq_ID,
            'salesman_weixin_ID'     => $info->weixin_ID,
            'salesman_name'          => $info->nickname,
            'salesman_head_portrait' => $info->head_portrait,
            'status'                 => $info->status,
        ];
    }
}