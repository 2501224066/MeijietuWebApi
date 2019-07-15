<?php

namespace App\Console\Commands;

use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;
use App\Models\Tb\Platform;
use App\Models\Tb\Priceclassify;
use Illuminate\Console\Command;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:go';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试使用';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $start = 0;
        while ($start <= 250000) {
            echo $start;
            $start++;

            $p = mt_rand(400,600)."0";
            echo " ".$p."\n";
            GoodsPrice::wherePrice(5000)
                ->offset(0)
                ->limit(1)
                ->update([
                'price' => $p
            ]);
        }

    }
}
