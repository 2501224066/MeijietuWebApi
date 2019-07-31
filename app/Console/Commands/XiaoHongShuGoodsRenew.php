<?php

namespace App\Console\Commands;

use App\Models\Data\Goods;
use App\Models\Data\GoodsPrice;
use App\Models\Attr\Filed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class xiaoHongShuGoodsRenew extends Command
{
    protected $signature = 'renew:xhs-goods {num}';

    protected $description = '导入小红书数据';

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

            $v = DB::connection('xiaohongshu_mongodb')
                ->collection('XiaoHongShu_Analysis')
                ->offset(($start - 1))
                ->limit(1)
                ->first();
            // 未查到退出循环
            if (!$v) {
                echo " NULL\n";
                continue;
            }

            $goods = Goods::where('room_ID', $v['BasicInfo']['XiaoHongShu_Id'])->first();// 房间号唯一标识
            // 存在即更新数据
            if ($goods) {
                echo " " . $goods->goods_id . " renew\n";
                $goods->fans_num        = $v['BasicInfo']['Fans_Num'];
                $goods->follows_num     = $v['BasicInfo']['Follows_Num'];
                $goods->total_like_num  = $v['BasicInfo']['Like_Num'];
                $goods->avg_like_num    = $v['Avg_Like_Num'];
                $goods->max_like_num    = $v['Max_Like_Num'];
                $goods->avg_comment_num = $v['Avg_Comment_Num'];
                $goods->max_comment_num = $v['Max_Comment_Num'];
                $goods->level_name      = $v['BasicInfo']['Level_Name'];
                $goods->notes_num       = $v['BasicInfo']['Notes_Num'];
                $goods->save();
            } else {
                // 不存在即创建
                echo " insert\n";
                DB::transaction(function () use ($v) {

                    $time = date('Y-m-d H:i:s');

                    $filed_arr = [8, 42, 43, 91, 99, 101, 102, 104, 105, 106, 107, 108, 109, 111, 112, 113, 115, 116, 117, 118, 119, 120];
                    $filed_id  = $filed_arr[array_rand($filed_arr)];

                    $goodsId = Goods::insertGetId([
                        'goods_num'       => createNum('GOODS'),
                        'title'           => $v['BasicInfo']['XiaoHongShu__NickName'],
                        'room_ID'         => $v['BasicInfo']['XiaoHongShu_Id'],
                        'html_title'      => $v['BasicInfo']['XiaoHongShu__NickName'],
                        'title_about'     => $v['BasicInfo']['Description'] ? $v['BasicInfo']['Description'] : '更多咨询敬请关注',
                        'qq_ID'           => '1001001001',
                        'modular_id'      => 3,
                        'modular_name'    => '视频营销 ',
                        'theme_id'        => 5,
                        'theme_name'      => '短视频',
                        'filed_id'        => $filed_id,
                        'filed_name'      => Filed::whereFiledId($filed_id)->value('filed_name'),
                        'platform_id'     => 4,
                        'platform_name'   => '小红书',
                        'avatar_url'      => $v['BasicInfo']['Avatar_Url'],
                        'link'            => $v['BasicInfo']['Url'],
                        'fans_num'        => $v['BasicInfo']['Fans_Num'],
                        'follows_num'     => $v['BasicInfo']['Follows_Num'],
                        'total_like_num'  => $v['BasicInfo']['Like_Num'],
                        'avg_like_num'    => $v['Avg_Like_Num'],
                        'max_like_num'    => $v['Max_Like_Num'],
                        'avg_comment_num' => $v['Avg_Comment_Num'],
                        'max_comment_num' => $v['Max_Comment_Num'],
                        'level_name'      => $v['BasicInfo']['Level_Name'],
                        'notes_num'       => $v['BasicInfo']['Notes_Num'],
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
                        'priceclassify_id'   => 18,
                        'priceclassify_name' => '单篇',
                        'price'              => $v['Price_TuWen']
                    ]);

                    GoodsPrice::create([
                        'goods_id'           => $goodsId,
                        'priceclassify_id'   => 19,
                        'priceclassify_name' => '合集',
                        'price'              => $v['Price_TuWen'] * 10
                    ]);

                    GoodsPrice::create([
                        'goods_id'           => $goodsId,
                        'priceclassify_id'   => 20,
                        'priceclassify_name' => '视频',
                        'price'              => $v['Price_Video']
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
