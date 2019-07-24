<?php

namespace App\Console\Commands;

use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;
use App\Models\Tb\Platform;
use App\Models\Tb\Priceclassify;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
        $start = -1;
        while ($start < 17000) {
            echo $start;
            $start ++;

         //TODO...
        }

    }
}
