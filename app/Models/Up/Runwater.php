<?php


namespace App\Models\Up;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class Runwater extends Model
{
    protected $table = 'up_runwater';

    protected $guarded = [];

    protected $primaryKey = 'runwarer_id';

    const TYPE = [
        '充值' => 1,
        '提现' => 2,
        '交易' => 3
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

    /**
     * 生成流水单
     */
    public static function createRunwater($money)
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

        // 订单数自增
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
            throw new Exception('重复回调');

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
                Log::info('金额异常操作失败:' . json_encode($data) . "\n");
                throw new Exception();
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
                $time = date('Y-m-d H:i:s');
                Wallet::whereUid($uid)->update([
                    'available_money' => $money,
                    'chang_lock' => createWalletChangLock($uid, $money, $time),
                    'time' => $time
                ]);
            } catch (\Exception $e) {
                Log::info('连连回调成功操作失败:' . json_encode($data) . "\n");
                throw new Exception();
            }
        });
    }
}