<?php


namespace App\Jobs;


use App\Models\Dt\Demand;
use App\Models\Indent\IndentInfo;
use App\Models\Up\Runwater;
use App\Models\Up\Wallet;
use App\Service\Pub;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class DemandSettlement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $demandNum;

    public function __construct($demandNum)
    {
        $this->demandNum = $demandNum;
    }

    /**
     * 需求结算操作
     * @throws \Throwable
     */
    public function handle()
    {
        $demandNum = $this->demandNum;

        DB::transaction(function () use ($demandNum) {
            try {
                // 需求数据 *加锁
                $demandData = Demand::whereDemandNum($demandNum)->lockForUpdate()->first();
                // 订单不存在跳出
                if (!$demandData) throw new Exception('订单不存在');
                // 检查需求状态
                Pub::checkParm($demandData->status, Demand::STATUS['完成'], '需求状态错误');
                // 校验卖家钱包状态
                Wallet::checkStatus($demandData->uid, Wallet::STATUS['启用']);
                // 校验卖家修改校验锁
                Wallet::checkChangLock($demandData->uid);
                // 公共钱包资金减少
                Wallet::updateWallet(Wallet::CENTERID, $demandData->price, Wallet::UP_OR_DOWN['减少']);
                // 卖家钱包资金增加
                Wallet::updateWallet($demandData->uid, $demandData->price, Wallet::UP_OR_DOWN['增加']);
                // 生成交易流水
                Runwater::createTransRunwater(Wallet::CENTERID,
                    $demandData->uid,
                    Runwater::TYPE['需求结算'],
                    Runwater::DIRECTION['转入'],
                    $demandData->price,
                    $demandData->bind_indent_id);
                // 修改订单信息
                $demandData->status = Demand::STATUS['结算'];
                if (!$demandData->save()) throw new Exception('需求结算失败');
            } catch (\Exception $e) {
                Log::notice('需求' . $demandNum . '结算失败 ' . $e->getMessage());
            }
        });

        Log::info('需求' . $demandNum . '完成结算');
    }
}