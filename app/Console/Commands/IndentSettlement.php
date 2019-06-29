<?php

namespace App\Console\Commands;

use App\Models\Indent\IndentInfo;
use App\Models\Log\LogIndentSettlement;
use App\Models\Up\Runwater;
use App\Models\Up\Wallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class IndentSettlement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indent:settlement {--queue=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '订单结算-延迟打款';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 长连接订阅
        $redis = Redis::connection('publisher');
        // 接受Key过期消息
        $redis->psubscribe(['__keyevent@*__:expired'], function ($OverdueKey) {
            Log::info('【Redis】 Key ' . $OverdueKey . ' 过期' . "\n");
            // 判断当前过期Key是否为 交易完成延迟打款Key
            if (!strstr($OverdueKey, 'TRANSACTION_COMPLETE_DELAY_PAYMENT:')) return false;
            // 取出订单号
            $indentNum = trim($OverdueKey, 'TRANSACTION_COMPLETE_DELAY_PAYMENT:');
            // 订单数据
            $indentData = IndentInfo::whereIndentNum($indentNum)->first();
            // 订单不存在跳出
            if (!$indentData) return false;

            try {
                // 检查订单状态
                IndentInfo::checkIndentStatus($indentData->status, IndentInfo::STATUS['全部完成']);
                // 资金操作
                $time = date('Y-m-d H:i:s');
                // 赔偿保证费
                $C = $indentData->compensate_fee;
                // 卖家获得资金 (抵押赔偿保证费 + 订单数据中卖家收入)
                $sellerM = $C + $indentData->seller_income;
                // 公共钱包退还
                $centerM = $sellerM;
                // 公共钱包资金减少
                $centerMoney = Wallet::whereUid(Wallet::CENTERID)->value('available_money') - $centerM;
                Wallet::whereUid(Wallet::CENTERID)->update([
                    'available_money' => $centerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock(Wallet::CENTERID, $centerMoney, $time)
                ]);
                // 卖家钱包资金增加
                $sellerMoney = Wallet::whereUid($indentData->seller_id)->value('available_money') + $sellerM;
                Wallet::whereUid($indentData->seller_id)->update([
                    'available_money' => $sellerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock($indentData->seller_id, $sellerMoney, $time)
                ]);
                // 生成交易流水
                $key         = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
                $runwaterNum = createRunwaterNum($key);
                Runwater::create([
                    'runwater_num' => $runwaterNum,
                    'form_uid'     => Wallet::CENTERID,
                    'to_uid'       => $indentData->seller_id,
                    'indent_id'    => $indentData->indent_id,
                    'indent_num'   => $indentData->indent_num,
                    'type'         => Runwater::TYPE['订单完成结算'],
                    'direction'    => Runwater::DIRECTION['转入'],
                    'money'        => $sellerM,
                    'status'       => Runwater::STATUS['成功']
                ]);
                Cache::increment($key);

                // 修改订单信息
                $indentData->status = IndentInfo::STATUS['已结算'];
                $indentData->save();

                // 完成结算
                Log::info('【订单结算】 订单号 ' . $indentNum . ' 完成结算' . "\n");
            } catch (\Exception $e) {
                Log::info('【订单结算】 结算失败:订单号 ' . $indentNum . "\n");
                LogIndentSettlement::create([
                    'indent_num' => $indentNum,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            return true;
        });

        return true;
    }
}
