<?php

namespace App\Jobs;

use App\Models\Indent\IndentInfo;
use App\Models\Up\Runwater;
use App\Models\Up\Wallet;
use App\Service\Pub;
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

    public function handle()
    {
        // 订单数据
        $indentData = IndentInfo::whereIndentNum($this->indentNum)->first();

        DB::transaction(function () use ($indentData) {
            try {
                // 订单不存在跳出
                if (!$indentData) throw new Exception('订单不存在');

                // 检查订单状态
                Pub::checkStatus($indentData->status, IndentInfo::STATUS['全部完成']);

                $time = date('Y-m-d H:i:s');
                // 赔偿保证费
                $C = $indentData->compensate_fee;
                // 卖家获得资金 (抵押赔偿保证费 + 订单数据中卖家收入)
                $sellerM = $C + $indentData->seller_income;
                // 公共钱包退还
                $centerM = $sellerM;

                // 公共钱包资金减少
                $centerMoney = Wallet::whereUid(Wallet::CENTERID)->value('available_money') - $centerM;
                DB::table('up_wallet')
                    ->where('uid', Wallet::CENTERID)->update([
                        'available_money' => $centerMoney,
                        'time'            => $time,
                        'change_lock'     => createWalletChangeLock(Wallet::CENTERID, $centerMoney, $time)
                    ]);

                // 卖家钱包资金增加
                $sellerMoney = Wallet::whereUid($indentData->seller_id)->value('available_money') + $sellerM;
                DB::table('up_wallet')
                    ->where('uid', $indentData->seller_id)
                    ->update([
                        'available_money' => $sellerMoney,
                        'time'            => $time,
                        'change_lock'     => createWalletChangeLock($indentData->seller_id, $sellerMoney, $time)
                    ]);

                // 生成交易流水
                $key         = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
                $runwaterNum = createRunwaterNum($key);
                DB::table('up_runwater')
                    ->insert([
                        'runwater_num' => $runwaterNum,
                        'from_uid'     => Wallet::CENTERID,
                        'to_uid'       => $indentData->seller_id,
                        'indent_id'    => $indentData->indent_id,
                        'indent_num'   => $indentData->indent_num,
                        'type'         => Runwater::TYPE['订单完成结算'],
                        'direction'    => Runwater::DIRECTION['转入'],
                        'money'        => $sellerM,
                        'status'       => Runwater::STATUS['成功'],
                        'updated_at'   => $time
                    ]);
                Cache::increment($key);

                // 修改订单信息
                $indentData->status = IndentInfo::STATUS['已结算'];
                $indentData->save();
            } catch (\Exception $e) {
                Log::info('【订单结算】 单号:' . $this->indentNum . ' 报错:' . $e->getMessage());
            }
        });
    }
}
