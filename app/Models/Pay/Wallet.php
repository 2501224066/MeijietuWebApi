<?php


namespace App\Models\Pay;


use App\Jobs\SendSms;
use App\Models\Data\News;
use App\Models\User;
use App\Server\Captcha;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * App\Models\Pay\Wallet
 *
 * @property int $wallet_id
 * @property int $uid
 * @property float $available_money 可用资金
 * @property string $change_lock 修改校验锁
 * @property int $status 钱包状态 0=禁用 1=启用
 * @property string|null $remark 备注
 * @property string $time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pay\Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pay\Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pay\Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pay\Wallet whereAvailableMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pay\Wallet whereChangeLock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pay\Wallet whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pay\Wallet whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pay\Wallet whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pay\Wallet whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pay\Wallet whereWalletId($value)
 * @mixin \Eloquent
 */
class Wallet extends Model
{
    protected $table = 'pay_wallet';

    protected $primaryKey = 'wallet_id';

    protected $guarded = [];

    public $timestamps = false;

    const CENTERID = 1; //中间账户ID

    const STATUS = [
        '启用' => 1,
        '禁用' => 0
    ];

    const UP_OR_DOWN = [
        '增加' => 1,
        '减少' => 0
    ];

    /**
     * 生成钱包
     * @param string $uid 用户id
     */
    public static function createWallet($uid)
    {
        if ($count = self::whereUid($uid)->count())
            throw new Exception('钱包已创建');

        $time = date('Y-m-d H:i:s');
        $re   = self::create([
            'uid'             => $uid,
            'available_money' => 0,
            'change_lock'     => createWalletChangeLock($uid, 0, $time),
            'time'            => $time
        ]);
        if (!$re)
            throw new Exception('生成钱包失败');

        // 消息推送
        News::put($uid, "资金账户创建成功");
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
        if (!self::whereUid($uid)->count())
            throw new Exception('钱包未创建');

        if (self::whereUid($uid)->value('status') != $status)
            throw new Exception('钱包状态错误');

        return true;
    }

    /**
     * 校验修改校验锁
     * @param string $uid 用户id
     */
    public static function checkChangLock($uid)
    {
        $wallet = self::whereUid($uid)->first();
        if (createWalletChangeLock($uid, $wallet->available_money, $wallet->time) != $wallet->change_lock) {
            Log::notice( '【资金】 用户 ' . User::whereUid($uid)->value('nickname') . ' 资金钱包异常，校验修改校验锁失败');
            throw new Exception('资金钱包异常');
        }
    }

    /**
     * 钱包余额是够足够购买
     * @param float $money 金额
     */
    public static function hasEnoughMoney($money)
    {
        $available_money = self::whereUid(JWTAuth::user()->uid)->value('available_money');
        if ($available_money < $money)
            throw new Exception('钱包余额不足');
    }

    /**
     * 修改钱包数据
     * @param string $uid 用户id
     * @param float $money 金额
     * @param int $upOrDown 增加或减少
     */
    public static function updateWallet($uid, $money, $upOrDown)
    {
        $time            = date('Y-m-d h:i:s');
        $wallet          = Wallet::whereUid($uid)->first();
        $available_money = $upOrDown == self::UP_OR_DOWN['增加'] ? $wallet->available_money + $money : $wallet->available_money - $money;

        $wallet->available_money = $available_money;
        $wallet->time            = $time;
        $wallet->change_lock     = createWalletChangeLock($uid, $available_money, $time);
        if (!$wallet->save())
            throw new Exception('修改钱包数据失败');

        Log::info('【资金】 ' . User::whereUid($uid)->value('nickname') . "钱包资金" . $upOrDown ? '增加' : '减少' . $money . '元，变更后资金' . $available_money);
        self::sms($uid, $money, $upOrDown, $available_money);
    }

    /**
     * 发送资金变动短信
     * @param string $uid 用户id
     * @param float $money 变动资金
     * @param int $upOrDown 方向
     * @param float $available_money 计算后金额
     */
    public static function sms($uid, $money, $upOrDown, $available_money)
    {
        // 中心账号资金变动不发送短信
        if ($uid != self::CENTERID) {

            $user = User::whereUid($uid)->first();
            SendSms::dispatch(Captcha::TYPE['资金变动'],
                $user->phone,
                [
                    'name'      => $user->nickname,
                    'money'     => $money,
                    'direction' => $upOrDown == self::UP_OR_DOWN['增加'] ? '转入' : '转出',
                    'amount'    => $available_money
                ])->onQueue('SendSms');
        }
    }
}