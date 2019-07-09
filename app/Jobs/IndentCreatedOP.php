<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class IndentCreatedOP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $info;

    public function __construct($info)
    {
        $this->info = $info;
    }

    public function handle()
    {
        // 删除购物车中商品
        $info = $this->info;

        foreach ($info as $i) {
            DB::table('nb_shopcart')
                ->where('goods_id', $i['goods_id'])
                ->delete();
        }
    }
}
