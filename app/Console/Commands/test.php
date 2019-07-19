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
        /*$start = 0;
        while ($start <= 30000) {
            echo $start;
            $start++;

            // 头像
            $g = Goods::where('qq_ID', '1001001001')
                ->where('goods_id','>','80000')
                ->offset(0)
                ->limit(1)
                ->first();

            if(!$g){
                echo " null";
            }

            if ($g->avatar_url) {
                try {
                    $imgContent = file_get_contents($g->avatar_url);
                    $path       = "images/head_portrait/" . str_random(30) . ".jpg";
                    echo " ".$g->goods_id." ".$path."\n";
                    $re         = Storage::put($path, $imgContent);
                    if ($re) {
                        $g->qq_ID      = '2501001001';
                        $g->avatar_url = $path;
                        $g->save();
                    }
                }catch (\Exception $e){
                    echo " ".$g->goods_id." default"."\n";
                    $g->qq_ID      = '2501001001';
                    $g->avatar_url = 'images/head_portrait/teT9lkA17FB2XOq7kXFwnTozLSlwm8.jpg';
                    $g->save();
                }
            }*/

//            // 二维码
//            $g = Goods::where('weixin_ID','!=', "")
//                ->where('goods_id',$start)
//                ->first();
//
//            if(!$g){
//                echo " null \n";
//                continue;
//            }
//
//            $g->qrcode_url = 'https://open.weixin.qq.com/qr/code?username='.$g->weixin_ID;
//            $g->save();
//            echo " save \n";



        }

    }
}
