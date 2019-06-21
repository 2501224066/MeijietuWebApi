<?php


namespace App\Models\Up;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class Runwater extends Model
{
    protected $table = 'up_runwater';

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
        $key = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
        $runwaterNum = createRunwaterNum($key);

        $re = self::create([
            'runwater_num' => $runwaterNum,
            'to_uid' => JWTAuth::user()->uid,
            'type' => self::TYPE['充值'],
            'direction' => self::DIRECTION['转入'],
            'money' => htmlspecialchars($money),
        ]);
        if (!$re)
            throw new Exception('操作失败');

        // 订单数自增
        Cache::increment($key);

        return $runwaterNum;
    }
}