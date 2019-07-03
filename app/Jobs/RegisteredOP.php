<?php

namespace App\Jobs;

use App\Models\Up\Wallet;
use App\Models\User;
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

        try{
            // 分配客服
            User::withUsalesman($uid);
            // 生成钱包
            Wallet::createWallet($uid);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
}
