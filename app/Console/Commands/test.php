<?php

namespace App\Console\Commands;

use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;
use Illuminate\Console\Command;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'text:go';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

         Goods::whereStatus(1)->delete();
         GoodsPrice::whereFloorPrice(0)->delete();


    }
}
