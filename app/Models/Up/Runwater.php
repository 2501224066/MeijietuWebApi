<?php


namespace App\Models\Up;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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

    // 回调价格异常
    public static function backAbnormal($data)
    {
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
    }

    // 回调成功修改属性
    public static function backSucc($data)
    {
        DB::transaction(function () use ($data) {
            // 记录流水
            $re = self::whereRunwaterNum($data['no_order'])
                ->update([
                    'status'                => self::STATUS['成功'],
                    'callback_time'         => date('Y-m-d H:i:s'),
                    'callback_oid_paybill'  => $data['oid_paybill'],
                    'callback_money_order'  => $data['money_order'],
                    'callback_settle_order' => $data['settle_date'],
                    'callback_pay_type'     => $data['pay_type'],
                    'callback_bank_code'    => $data['bank_code']
                ]);
            if (!$re)
                throw new Exception('记录流水失败');

            $uid = self::whereRunwaterNum($data['no_order'])->value('to_uid');

            // 校验修改校验锁
            $info = Wallet::checkChangLock($uid);

            // 修改资金
            $re2 = Wallet::whereUid($uid)
                ->updata([
                    'available_money' => $info['available_money'] + $data['money_order'],
                ]);
            if (!$re2)
                throw new Exception('修改资金失败');

            // 更新修改校验锁
            Wallet::saveChangLock($uid);
        });

        return true;
    }
}