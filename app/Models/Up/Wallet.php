<?php


namespace App\Models\Up;


use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class Wallet extends Model
{
    protected $table = 'up_wallet';

    protected $guarded = [];

    public $timestamps = false;

    // 检测是否拥有钱包
    protected static function checkHas($uid)
    {
        $count = self::whereUid($uid)->count();
        if($count)
            throw new Exception('已拥有钱包');

        return true;
    }

    // 生成钱包
    protected static function createWallet($uid)
    {
        $time = date('Y-m-d H:i:s');
        $re   = self::create([
            'uid'             => $uid,
            'available_money' => 0,
            'chang_lock'      => createWalletChangLock($uid, 0, $time),
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

        return [
            'available_money' => $re->available_money,
        ];
    }

    /**
     * 校验修改校验锁
     * @param $uid
     * @return mixed
     */
    public static function checkChangLock($uid)
    {
        $info = self::whereUid($uid)->first();
        if (createWalletChangLock($uid, $info->avaiable_money, $info->time) != $info->chang_lock)
            throw new Exception('修改校验锁校验失败');

        return $info;
    }

    /**
     * 更新修改校验锁
     */
    public static function saveChangLock($uid)
    {
        $info      = self::whereUid($uid)->first();
        $changLock = createWalletChangLock($uid, $info->avaiable_money, $info->time);
        $re        = Wallet::whereUid($uid)
            ->updata([
                'chang_lock' => $changLock
            ]);
        if (!$re)
            throw new Exception('更新修改校验锁失败');
    }
}