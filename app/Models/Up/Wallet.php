<?php


namespace App\Models\Up;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * App\Models\Up\Wallet
 *
 * @property int $wallet_id
 * @property int $uid
 * @property float $available_money 可用资金
 * @property string $change_lock 修改校验锁
 * @property int $status 钱包状态 0=禁用 1=启用
 * @property string|null $remark 备注
 * @property string $time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Wallet whereAvailableMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Wallet whereChangeLock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Wallet whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Wallet whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Wallet whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Wallet whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Wallet whereWalletId($value)
 * @mixin \Eloquent
 */
class Wallet extends Model
{
    protected $table = 'up_wallet';

    protected $primaryKey = 'wallet_id';

    protected $guarded = [];

    public $timestamps = false;

    const CENTERID = 0; //中间账户ID

    const STATUS = [
        '启用' => 1,
        '禁用' => 0
    ];

    // 检测是否拥有钱包
    public static function checkHas($uid, $status)
    {
        if (self::whereUid($uid)->count() != $status)
            throw new Exception('钱包存在状态错误');

        return true;
    }

    // 生成钱包
    public static function createWallet($uid)
    {
        $time = date('Y-m-d H:i:s');
        $re   = self::create([
            'uid'             => $uid,
            'available_money' => 0,
            'change_lock'      => createWalletChangeLock($uid, 0, $time),
            'time'            => $time
        ]);
        if (!$re)
            throw new Exception('生成钱包失败');

        return true;
    }

    // 钱包信息
    public static function info()
    {
        $re = self::whereUid(JWTAuth::user()->uid)->first();
        if (!$re)
            throw new Exception('未找到钱包信息');

        return ['available_money' => $re->available_money,];
    }

    // 校验钱包状态
    public static function checkStatus($uid, $status)
    {
        if (self::whereUid($uid)->value('status') != $status)
            throw new Exception('钱包状态错误');

        return true;
    }

    // 校验修改校验锁
    public static function checkChangLock($uid)
    {
        $wallet = self::whereUid($uid)->first();
        if (createWalletChangeLock($uid, $wallet->available_money, $wallet->time) != $wallet->change_lock) {
            self::whereUid($uid)->update([
                'status' => self::STATUS['禁用'],
                'remark' => '校验修改校验锁失败, 禁用钱包'
             ]);

            throw new Exception('校验修改校验锁失败, 禁用钱包, 请联系客服');
        }

        return true;
    }

    // 钱包余额是够足够购买
    public static function hasEnoughMoney($money)
    {
        $available_money = self::whereUid(JWTAuth::user()->uid)->value('available_money');
        if($available_money < $money)
            throw new Exception('钱包余额不足');

        return true;
    }
}