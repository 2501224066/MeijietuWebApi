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
        '失败'  => 2,
        '异常'  => 3
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

    // 检测是否为重复回调
    public static function checkMoreBack($callback_oid_paybill)
    {
        return self::whereCallbackOidPaybill($callback_oid_paybill)->count();
    }

    // 回调流水异常
    public static function backAbnormalOP($data)
    {
        DB::transaction(function () use ($data) {
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
                $uid = self::whereRunwaterNum($data['no_order'])->value('to_uid');
                Wallet::whereUid($uid)->updata([
                    'status' => Wallet::STATUS['禁用'],
                    'remark' => '充值回调金额异常'
                ]);
            } catch (\Exception $e) {
                Log::info('连连回调流水异常操作失败:' . json_encode($data) . "\n");
                throw new Exception();
            }
        });
    }

    // 回调成功操作
    public static function backSuccOP($data)
    {

        DB::transaction(function () use ($data) {
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

                $uid = self::whereRunwaterNum($data['no_order'])->value('to_uid');

                // 校验修改校验锁
                $info = Wallet::whereUid($uid)->first();
                if (createWalletChangLock($uid, $info->avaiable_money, $info->time) != $info->chang_lock) {
                    // 校验失败，禁用钱包
                    Wallet::whereUid($uid)->update([
                        'status' => Wallet::STATUS['禁用'],
                        'remark' => '校验修改检验锁失败，禁用钱包'
                    ]);
                } else {
                    // 修改资金
                    Wallet::whereUid($uid)
                        ->updata([
                            'available_money' => $info['available_money'] + $data['money_order'],
                            'time'            => date('Y-m-d H:i:s')
                        ]);

                    // 更新修改校验锁
                    Wallet::whereUid($uid)->updata(['chang_lock' => createWalletChangLock($uid, $info->avaiable_money, $info->time)]);
                }
            } catch (\Exception $e) {
                Log::info('连连回调成功操作失败:' . json_encode($data) . "\n");
                throw new Exception();
            }
        });
    }
}