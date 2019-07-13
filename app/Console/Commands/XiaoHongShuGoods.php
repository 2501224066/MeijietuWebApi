<?php

namespace App\Console\Commands;

use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;
use App\Models\Tb\Filed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class xiaoHongShuGoods extends Command
{
    protected $signature = 'insert:xhs-goods {num}';

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

            // 重复不录入
            $v = DB::connection('xiaohongshu_mongodb')
                ->collection('XiaoHongShu_Analysis')
                ->offset(($start - 1))
                ->limit(1)
                ->first();
            if (!$v) {
                echo " NULL\n";
                continue;
            }

            // 房间号唯一标识
            $co = Goods::where('room_ID', $v['BasicInfo']['XiaoHongShu_Id'])->count();
            if ($co > 0) {
                echo " n\n";
                continue;
            }

            echo " y\n";
            DB::transaction(function () use ($v) {

                $time = date('Y-m-d H:i:s');

                $filed_id = mt_rand(91, 120);

                $goodsId = Goods::insertGetId([
                    'goods_num'       => createGoodsNnm('W'),
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
                    'link'            => $v['BasicInfo']['Url'],
                    'fans_num'        => $v['BasicInfo']['Fans_Num'],
                    'all_like_num'    => $v['BasicInfo']['Like_Num'],
                    'avg_like_num'    => $v['Avg_Like_Num'],
                    'max_like_num'    => $v['Max_Like_Num'],
                    'avg_comment_num' => $v['Avg_Comment_Num'],
                    'max_comment_num' => $v['Max_Comment_Num'],
                    'avatar_url'      => $v['BasicInfo']['Avatar_Url'],
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
                    'price'              => 0
                ]);

                GoodsPrice::create([
                    'goods_id'           => $goodsId,
                    'priceclassify_id'   => 20,
                    'priceclassify_name' => '视频',
                    'price'              => $v['Price_Video']
                ]);
            });
        }
    }
}
