<?php


namespace App\Models\Up;


use App\Models\Indent\IndentInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
 * @property int $type 类型 1=充值 2=提现 3=订单付款 4=支付赔偿保证费 5=取消订单全额退款 6=取消订单非全额退款 7=对方取消订单退款 8=订单完成结算 9=需求结算
 * @property int $direction 方向 1=转入 2=转出
 * @property float $money 金额
 * @property int $status 状态 0=进行中 1=成功 2=异常
 * @property string|null $callback_time 回调时间
 * @property string|null $callback_trade_no 交易凭证
 * @property float|null $callback_money_order 交易金额
 * @property string|null $callback_pay_type 支付方式 0:余额支付 1:网银借记卡支付 8:网银信用卡支付 9:企业网银信用卡支付 2:快捷支付(借记卡) 3:快捷支付(信用卡) D:认证支付 I:微信主扫 L:支付宝主扫 aliPay:支付宝支付
 * @property string|null $callback_bank_code 银行编号
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereCallbackBankCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereCallbackMoneyOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereCallbackPayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereCallbackTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Up\Runwater whereCallbackTradeNo($value)
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
        '订单完成结算'    => 8,
        '需求结算'      => 9
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

    /**
     * 生成充值流水
     * @param float $money 充值金额
     * @return string
     */
    public static function createRechargeRunwater($money): string
    {
        $runwaterNum = createNum('RUNWATER');
        $re          = self::create([
            'runwater_num' => $runwaterNum,
            'to_uid'       => JWTAuth::user()->uid,
            'type'         => self::TYPE['充值'],
            'direction'    => self::DIRECTION['转入'],
            'money'        => htmlspecialchars($money),
        ]);
        if (!$re)
            throw new Exception('操作失败');

        return $runwaterNum;
    }

    /**
     * 检测流水是否存在
     * @param string $runwater_num 流水编号
     * @return Runwater|\Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public static function checkHas($runwater_num): Runwater
    {
        $re = self::whereRunwaterNum($runwater_num)->first();
        if (!$re)
            throw new Exception('无此流水');

        return $re;
    }

    /**
     * 检测是否为重复回调
     * @param string $callback_oid_paybill 回调单号
     */
    public static function checkMoreBack($callback_oid_paybill)
    {
        $count = self::whereCallbackOidPaybill($callback_oid_paybill)->count();
        if ($count)
            throw new Exception('重复回调 连连支付单号:' . $callback_oid_paybill);
    }

    /**
     * 提现操作
     * @param string $uid 用户id
     * @param float $money 提现金额
     * @throws \Throwable
     */
    public static function extractOP($uid, $money)
    {
        DB::transaction(function () use ($uid, $money) {
            try {
                // 校验钱包状态
                Wallet::checkStatus($uid, Wallet::STATUS['启用']);
                // 校验修改校验锁
                Wallet::checkChangLock($uid);
                // 钱包余额是够足够
                Wallet::hasEnoughMoney($money);
                // 用户资金减少
                Wallet::updateWallet($uid, $money, Wallet::UP_OR_DOWN['减少']);
                // 生成交易流水
                Runwater::createTransRunwater($uid,
                    null,
                    Runwater::TYPE['提现'],
                    Runwater::DIRECTION['转出'],
                    $money,
                    null,
                    Runwater::STATUS['进行中']
                );
            } catch (\Exception $e) {
                throw new Exception('操作失败');
            }
        });
    }

    /**
     * 生成交易流水
     * @param string $from_uid 来源uid
     * @param string $to_uid 去往uid
     * @param int $type 类型
     * @param int $direction 方向
     * @param float $money 流水金额
     * @param string $indent_id 订单id
     * @param int $status 流水状态
     */
    public static function createTransRunwater($from_uid, $to_uid, $type, $direction, $money, $indent_id = null, $status = self::STATUS['成功'])
    {
        $re = Runwater::create([
            'runwater_num' => createNum('RUNWATER'),
            'from_uid'     => $from_uid,
            'to_uid'       => $to_uid,
            'type'         => $type,
            'direction'    => $direction,
            'money'        => $money,
            'status'       => $status,
            'indent_id'    => $indent_id,
            'indent_num'   => $indent_id ? IndentInfo::whereIndentId($indent_id)->value('indent_num') : null
        ]);
        if (!$re)
            throw new Exception('生成交易流水失败');
    }

    /**
     * 充值成功流水修改
     * @param string $runwaterNum 流水编号
     * @param string $callbackRradeNo 交易单号（支付平台单号）
     * @param float $callbackMoneyOrder 回调金额
     * @param string $callbackPayType 交易方式类型
     * @param string $callbackBankCode 回调银行编号
     */
    public static function rechargeBackSuccessUpdate($runwaterNum, $callbackRradeNo, $callbackMoneyOrder, $callbackPayType, $callbackBankCode = null)
    {
        $b = self::whereRunwaterNum($runwaterNum)
            ->update([
                'status'               => self::STATUS['成功'],
                'callback_time'        => date('Y-m-d H:i:s'),
                'callback_trade_no'    => $callbackRradeNo,
                'callback_money_order' => $callbackMoneyOrder,
                'callback_pay_type'    => isset($callbackPayType) ? $callbackPayType : null,
                'callback_bank_code'   => isset($callbackBankCode) ? $callbackBankCode : null
            ]);
        if (!$b)
            throw new Exception('流水修改失败');
    }
}