<?php

namespace App\Console\Commands;

use App\Models\Count\Day;
use App\Models\Data\Demand;
use App\Models\Data\Goods;
use App\Models\Data\IndentInfo;
use App\Models\User;
use Illuminate\Console\Command;
use Yansongda\Pay\Log;

class CountDay extends Command
{

    protected $signature = 'count:day';

    protected $description = '日数据统计';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $t = date("Y-m-d", strtotime("-1 day"));

        $c['register'] = User::where('created_at', 'like', '%' . $t . '%')->count();
        $c['goods']    = Goods::where('created_at', 'like', '%' . $t . '%')->count();
        $c['indent']   = IndentInfo::where('create_time', 'like', '%' . $t . '%')->count();
        $c['demand']   = Demand::where('created_at', 'like', '%' . $t . '%')->count();
        $c['time']     = $t;
        Day::create($c);

        Log::info('【日统计】 ' . $t . '统计完成');
    }
}
