<?php


namespace App\Models\Up;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\Up\Runwater
 *
 * @property int $runwater_id 流水id
 * @property string $runwater_num 流水单号
 * @property int|null $from_uid 来源处
 * @property int|null $to_uid 去往处
 * @property int|null $indent_id 订单id
 * @property string|null $indent_num 订单号
 * @property int $type 类型 1=充值 2=提现 3=订单付款 4=支付赔偿保证费 5=取消订单全额退款 6=取消订单非全额退款 7=对方取消订单退款 8=订单完成结算
 * @property int $direction 方向 1=转入 2=转出
 * @property float $money 金额
 * @property int $status 状态 0=进行中 1=成功 2=异常
 * @property string|null $callback_time 回调时间
 * @property string|null $callback_oid_paybill 连连支付单号
 * @property float|null $callback_money_order 交易金额
 * @property string|null $callback_settle_order 清算日期
 * @property string|null $callback_pay_type 支付方式 0:余额支付 1:网银借记卡支付 8:网银信用卡支付 9:企业网银信用卡支付 2:快捷支付(借记卡) 3:快捷支付(信用卡) D:认证支付 I:微信主扫 L:支付宝主扫
 * @property string|null $callback_bank_code 银行编号
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereCallbackBankCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereCallbackMoneyOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereCallbackOidPaybill($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereCallbackPayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereCallbackSettleOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereCallbackTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereFromUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereIndentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereIndentNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereRunwaterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereRunwaterNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereToUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Runwater extends Model
{
    protected $table = 'up_runwater';

    protected $guarded = [];

    protected $primaryKey = 'runwarer_id';

    const TYPE = [
        '充值'        => 1,
        '提现'        => 2,
        '订单付款'      => 3,
        '支付赔偿保证费'   => 4,
        '取消订单全额退款'  => 5,
        '取消订单非全额退款' => 6,
        '对方取消订单退款'  => 7,
        '订单完成结算'    => 8
    ];

    const DIRECTION = [
        '转入' => 1,
        '转出' => 2
    ];

    const STATUS = [
        '进行中' => 0,
        '成功'  => 1,
        '异常'  => 2
    ];

    // 当天订单数
    public static function todayRunwaterCount($key)
    {
        if (!Cache::has($key))
            Cache::put($key, 1, 60 * 24);

        return sprintf("%04d", Cache::get($key));
    }

    //生成充值流水
    public static function createRechargeRunwater($money)
    {
        $key         = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
        $runwaterNum = createRunwaterNum($key);

        $re = self::create([
            'runwater_num' => $runwaterNum,
            'to_uid'       => JWTAuth::user()->uid,
            'type'         => self::TYPE['充值'],
            'direction'    => self::DIRECTION['转入'],
            'money'        => htmlspecialchars($money),
        ]);
        if (!$re)
            throw new Exception('操作失败');

        // 单数自增
        Cache::increment($key);

        return $runwaterNum;
    }

    // 检测流水是否存在
    public static function checkHas($runwater_num)
    {
        $re = self::whereRunwaterNum($runwater_num)->first();
        if (!$re)
            throw new Exception('无此流水');

        return $re;
    }

    // 检测是否为重复回调
    public static function checkMoreBack($callback_oid_paybill)
    {
        $count = self::whereCallbackOidPaybill($callback_oid_paybill)->count();
        if ($count)
            throw new Exception('重复回调 连连支付单号:' . $callback_oid_paybill);

        return true;
    }

    // 回调金额异常
    public static function backAbnormalOP($data, $uid)
    {
        DB::transaction(function () use ($data, $uid) {
            try {
                // 添加异常记录
                self::whereRunwaterNum($data['no_order'])
                    ->update([
                        'status'                => self::STATUS['异常'],
                        'callback_time'         => date('Y-m-d H:i:s'),
                        'callback_oid_paybill'  => $data['oid_paybill'],
                        'callback_money_order'  => $data['money_order'],
                        'callback_settle_order' => $data['settle_date'],
                        'callback_pay_type'     => $data['pay_type'],
                        'callback_bank_code'    => $data['bank_code']
                    ]);

                // 钱包禁用
                Wallet::whereUid($uid)->updata([
                    'status' => Wallet::STATUS['禁用'],
                    'remark' => '充值回调金额异常'
                ]);
            } catch (\Exception $e) {
                throw new Exception('【连连回调】 金额异常操作失败:' . json_encode($data) . "\n");
            }
        });
    }

    // 回调成功操作
    public static function backSuccOP($data, $uid)
    {
        DB::transaction(function () use ($data, $uid) {
            try {
                // 记录流水
                self::whereRunwaterNum($data['no_order'])
                    ->update([
                        'status'                => self::STATUS['成功'],
                        'callback_time'         => date('Y-m-d H:i:s'),
                        'callback_oid_paybill'  => $data['oid_paybill'],
                        'callback_money_order'  => $data['money_order'],
                        'callback_settle_order' => $data['settle_date'],
                        'callback_pay_type'     => $data['pay_type'],
                        'callback_bank_code'    => $data['bank_code']
                    ]);

                // 修改资金
                $money = Wallet::whereUid($uid)->value('available_money') + $data['money_order'];
                $time  = date('Y-m-d H:i:s');
                Wallet::whereUid($uid)->update([
                    'available_money' => $money,
                    'change_lock'     => createWalletChangeLock($uid, $money, $time),
                    'time'            => $time
                ]);
            } catch (\Exception $e) {
                throw new Exception('【连连回调】 修改金额失败:' . json_encode($data) . "\n");
            }
        });
    }

    // 提现操作
    public static function extractOP($uid, $money)
    {
        $time = date('Y-m-d H:i:s');
        DB::transaction(function () use ($uid, $money, $time) {
            try {
                // 用户资金扣除
                $userMoney = Wallet::whereUid($uid)->value('available_money') - $money;
                Wallet::whereUid($uid)->update([
                    'available_money' => $userMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock($uid, $userMoney, $time)
                ]);

                // 生成交易流水
                $key         = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
                $runwaterNum = createRunwaterNum($key);
                Runwater::create([
                    'runwater_num' => $runwaterNum,
                    'from_uid'     => $uid,
                    'type'         => Runwater::TYPE['提现'],
                    'direction'    => Runwater::DIRECTION['转出'],
                    'money'        => $money,
                    'status'       => Runwater::STATUS['进行中']
                ]);
                Cache::increment($key);
            } catch (\Exception $e) {
                throw new Exception('操作失败');
            }

        });

        return true;
    }
}