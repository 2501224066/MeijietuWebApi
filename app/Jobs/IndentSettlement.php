<?php

namespace App\Jobs;

use App\Models\Indent\IndentInfo;
use App\Models\Up\Runwater;
use App\Models\Up\Wallet;
use App\Service\Pub;
use App\Service\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class IndentSettlement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $indentNum;

    public function __construct($indentNum)
    {
        $this->indentNum = $indentNum;
    }

    /**
     * 订单结算操作
     * @throws \Throwable
     */
    public function handle()
    {
        $indentNum = $this->indentNum;

        DB::transaction(function () use ($indentNum) {
            try {
                // 订单数据 *加锁
                $indentData = IndentInfo::whereIndentNum($indentNum)->lockForUpdate()->first();
                // 订单不存在跳出
                if (!$indentData) throw new Exception('订单不存在');
                // 检查订单状态
                Pub::checkParm($indentData->status, IndentInfo::STATUS['全部完成'], '订单状态错误');
                // 校验卖家钱包状态
                Wallet::checkStatus($indentData->seller_id, Wallet::STATUS['启用']);
                // 校验卖家修改校验锁
                Wallet::checkChangLock($indentData->seller_id);
                // 订单结算资金计算
                $countMoney = Transaction::indentSettlementCountMoney($indentData->compensate_fee, $indentData->seller_income);
                // 公共钱包资金减少
                Wallet::updateWallet(Wallet::CENTERID, $countMoney['centerDown'], Wallet::UP_OR_DOWN['减少']);
                // 卖家钱包资金增加
                Wallet::updateWallet($indentData->seller_id, $countMoney['sellerUp'], Wallet::UP_OR_DOWN['增加']);
                // 生成交易流水
                Runwater::createTransRunwater(Wallet::CENTERID,
                    $indentData->seller_id,
                    Runwater::TYPE['订单完成结算'],
                    Runwater::DIRECTION['转入'],
                    $countMoney['sellerUp'],
                    $indentData->indent_id);
                // 修改订单信息
                IndentInfo::updateIndent($indentData, IndentInfo::STATUS['已结算']);
            } catch (\Exception $e) {
                Log::notice('订单' . $indentNum . '结算失败 ' . $e->getMessage());
            }
        });

        Log::info('订单' . $indentNum . '完成结算');
    }
}
