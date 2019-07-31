<?php


namespace App\Console\Commands;


use App\Models\Data\Goods;
use App\Models\Data\GoodsPrice;
use App\Models\Attr\Filed;
use App\Models\Attr\Theme;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class weiBoGoodsRenew extends Command
{

    protected $signature = 'renew:weibo-goods {num}';


    protected $description = '更新初始微博商品数据';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $start = $this->argument('num');
        $end   = 20000;
        while ($start <= $end) {
            echo $start;
            $start++;

            $v = DB::connection('weibo_mongodb')
                ->collection('WeiBo_Analysis')
                ->offset($start)
                ->limit(1)
                ->first();
            // 未查到退出循环
            if (!$v) {
                echo " NULL\n";
                continue;
            }

            $link  = "https://weibo.com/" . $v['WeiBo_Uid']; // 唯一标识
            $goods = Goods::whereLink($link)->first();
            // 存在即更新数据
            if ($goods) {
                echo " " . $goods->goods_id . " renew\n";
                $goods->fans_num          = $v['BasicInfo']['Fans_Num'];
                $goods->total_like_num    = $v['Total_Like_Num_Last10'];
                $goods->avg_like_num      = $v['Avg_Like_Num_Last10'];
                $goods->max_like_num      = $v['Max_Like_Num_Last10'];
                $goods->avg_comment_num   = $v['Avg_Comment_Num_Last10'];
                $goods->total_comment_num = $v['Total_Comment_Num_Last10'];
                $goods->max_comment_num   = $v['Max_Comment_Num_Last10'];
                $goods->total_retweet_num = $v['Total_Retweet_Num_Last10'];
                $goods->avg_retweet_num   = $v['Avg_Retweet_Num_Last10'];
                $goods->max_retweet_num   = $v['Max_Retweet_Num_Last10'];
                $goods->follows_num       = $v['BasicInfo']['Follow_Count'];
                $goods->save();
            } else {
                // 不存在即创建
                echo " insert\n";
                DB::transaction(function () use ($v, $link) {

                    $time = date('Y-m-d H:i:s');

                    $theme_id  = mt_rand(3, 4);
                    $filed_arr = [10, 17, 33, 42, 47, 48, 49, 51, 52, 53, 54, 55, 56, 57, 58, 60, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97];
                    $filed_id  = $filed_arr[array_rand($filed_arr)];

                    $goodsId = Goods::insertGetId([
                        'goods_num'         => createNum('GOODS'),
                        'title'             => $v['BasicInfo']['WeiBo_Name'],
                        'html_title'        => $v['BasicInfo']['WeiBo_Name'],
                        'title_about'       => $v['BasicInfo']['Description'],
                        'link'              => $link,
                        'qq_ID'             => '2501001001',
                        'modular_id'        => 2,
                        'modular_name'      => '微博营销',
                        'theme_id'          => $theme_id,
                        'theme_name'        => Theme::whereThemeId($theme_id)->value('theme_name'),
                        'filed_id'          => $filed_id,
                        'filed_name'        => Filed::whereFiledId($filed_id)->value('filed_name'),
                        'fans_num'          => $v['BasicInfo']['Fans_Num'],
                        'total_like_num'    => $v['Total_Like_Num_Last10'],
                        'avg_like_num'      => $v['Avg_Like_Num_Last10'],
                        'max_like_num'      => $v['Max_Like_Num_Last10'],
                        'total_comment_num' => $v['Total_Comment_Num_Last10'],
                        'avg_comment_num'   => $v['Avg_Comment_Num_Last10'],
                        'max_comment_num'   => $v['Max_Comment_Num_Last10'],
                        'total_retweet_num' => $v['Total_Retweet_Num_Last10'],
                        'avg_retweet_num'   => $v['Avg_Retweet_Num_Last10'],
                        'max_retweet_num'   => $v['Max_Retweet_Num_Last10'],
                        'follows_num'       => $v['BasicInfo']['Follow_Count'],
                        'avatar_url'        => $v['BasicInfo']['Avatar_Url'],
                        'region_id'         => 1,
                        'region_name'       => '全国',
                        'reserve_status'    => 0,
                        'verify_status'     => 2,
                        'status'            => 1,
                        'created_at'        => $time,
                        'updated_at'        => $time
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
                        'price'              => $v['Price_ZhiFa'] ? $v['Price_ZhiFa'] : 0
                    ]);

                    GoodsPrice::create([
                        'goods_id'           => $goodsId,
                        'priceclassify_id'   => 8,
                        'priceclassify_name' => '微任务转发',
                        'price'              => $v['Price_ZhuanFa'] ? $v['Price_ZhuanFa'] : 0
                    ]);

                    // 头像转到oss
                    try {
                        $imgContent = file_get_contents($v['BasicInfo']['Avatar_Url']);
                        $path       = "images/head_portrait/" . str_random(30) . ".jpg";
                        echo " " . $path . "\n";
                        Storage::put($path, $imgContent);
                        Goods::whereGoodsId($goodsId)->update([
                            'avatar_url' => $path
                        ]);
                    } catch (\Exception $e) {
                        echo" default" . "\n";
                        Goods::whereGoodsId($goodsId)->update([
                            'avatar_url' => 'images/head_portrait/teT9lkA17FB2XOq7kXFwnTozLSlwm8.jpg'
                        ]);
                    }

                });
            }
        }
    }
}