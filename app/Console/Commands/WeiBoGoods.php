<?php


namespace App\Console\Commands;


use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;
use App\Models\Tb\Filed;
use App\Models\Tb\Theme;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class weiBoGoods extends Command
{

    protected $signature = 'insert:weibo-goods {num}';


    protected $description = '导入微博商品';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $start = $this->argument('num');
        $end   = $start + 5000;
        while ($start <= $end) {
            echo $start;
            $start++;

            $v = DB::connection('weibo_mongodb')
                ->collection('WeiBo_Analysis')
                ->offset(($start - 1))
                ->limit(1)
                ->first();
            if (!$v) {
                echo " NULL\n";
                continue;
            }


            $link = "https://weibo.com/" . $v['WeiBo_Uid'];
            $co   = Goods::whereLink($link)->count();
            if ($co > 0) {
                echo " n\n";
                continue;
            }

            echo " y\n";
            DB::transaction(function () use ($v, $link) {

                $time = date('Y-m-d H:i:s');

                $title_about = ($v['BasicInfo']['Description'] && $this->have_special_char($v['BasicInfo']['Description'])) ? $v['BasicInfo']['Description'] : '更多资讯敬请关注';

                $theme_id = mt_rand(3, 4);
                $filed_id = mt_rand(47, 87);

                $goodsId = Goods::insertGetId([
                    'goods_num'       => createGoodsNnm('B'),
                    'title'           => $v['BasicInfo']['WeiBo_Name'],
                    'html_title'      => $v['BasicInfo']['WeiBo_Name'],
                    'title_about'     => $title_about,
                    'link'            => $link,
                    'qq_ID'           => '1001001001',
                    'modular_id'      => 2,
                    'modular_name'    => '微博营销',
                    'theme_id'        => $theme_id,
                    'theme_name'      => Theme::whereThemeId($theme_id)->value('theme_name'),
                    'filed_id'        => $filed_id,
                    'filed_name'      => Filed::whereFiledId($filed_id)->value('filed_name'),
                    'fans_num'        => $v['BasicInfo']['Fans_Num'],
                    'avg_like_num'    => $v['Avg_Like_Num_Last10'],
                    'avg_comment_num' => $v['Avg_Comment_Num_Last10'],
                    'avg_retweet_num' => $v['Avg_Retweet_Num_Last10'],
                    'avatar_url'      => $v['BasicInfo']['Avatar_Url'],
                    'region_id'       => 1,
                    'region_name'     => '全国',
                    'reserve_status'  => 0,
                    'verify_status'   => 2,
                    'status'          => 1,
                    'created_at'      => $time,
                    'updated_at'      => $time
                ]);

                GoodsPrice::create([
                    'goods_id'           => $goodsId,
                    'priceclassify_id'   => 12,
                    'priceclassify_name' => '微博直发',
                    'price'              => $v['Price_ZhiFa'] ? $v['Price_ZhiFa'] : 0
                ]);

                GoodsPrice::create([
                    'goods_id'           => $goodsId,
                    'priceclassify_id'   => 13,
                    'priceclassify_name' => '微博转发',
                    'price'              => $v['Price_ZhuanFa'] ? $v['Price_ZhuanFa'] : 0
                ]);

                GoodsPrice::create([
                    'goods_id'           => $goodsId,
                    'priceclassify_id'   => 7,
                    'priceclassify_name' => '微任务直发',
                    'price'              => 0
                ]);

                GoodsPrice::create([
                    'goods_id'           => $goodsId,
                    'priceclassify_id'   => 8,
                    'priceclassify_name' => '微任务转发',
                    'price'              => 0
                ]);
            });
        }
    }
}