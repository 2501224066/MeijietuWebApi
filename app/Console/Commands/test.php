<?php

namespace App\Console\Commands;


use App\Models\Data\Goods;
use App\Models\User;
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
        $start = 65000;
        while ($start < 1000000) {
            echo $start;
            $start++;

            // TODO...
            $t = Goods::where('uid', '!=', '')
                ->offset($start)
                ->limit(1)
                ->first();

            $num = createNum('GOODS');
            echo " " . $num . "\n";
            $t->goods_num = $num;
            $t->save();
        }

    }
}
