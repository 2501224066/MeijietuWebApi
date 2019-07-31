<?php

namespace App\Jobs;

use App\Models\Pay\Wallet;
use App\Models\User;
use App\Server\Salesman;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RegisteredOP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $uid = $this->uid;

        try {
            // 如果没有客服则分配客服
            if (!User::whereUid($uid)->value('salesman_id')) {
                Salesman::withSalesman($uid);
            }
            // 生成钱包
            Wallet::createWallet($uid);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
