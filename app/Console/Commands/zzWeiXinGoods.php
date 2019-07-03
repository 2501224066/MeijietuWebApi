<?php

namespace App\Console\Commands;

use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;
use App\Models\Tb\Filed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class zzWeiXinGoods extends Command
{

    protected $signature = 'zz:weixin-goods';


    protected $description = '导入微信数据';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {

        $start = 6280;
        while ($start < 14100) {
            echo $start;
            $start++;

            $v = DB::connection('weixin_mongodb')
                ->collection('WeiXin_OfficialAccount_Analysis')
                ->offset(($start - 1))
                ->limit(1)
                ->first();

                $co = Goods::where('weixin_ID', $v['OfficialAccount_ID'])->count();
                if ($co > 0) {
                    echo " n\n";
                    continue;
                }

                echo " y\n";
                DB::transaction(function () use ($v) {

                    $time = date('Y-m-d H:i:s');

                    $title_about = ($v['BasicInfo']['Description'] && $this->have_special_char($v['BasicInfo']['Description'])) ? $v['BasicInfo']['Description'] : '更多资讯敬请关注';

                    $filed_id = mt_rand(1, 13);

                    $goodsId = Goods::insertGetId([
                        'goods_num'       => createGoodsNnm('W'),
                        'title'           => $v['BasicInfo']['OfficialAccount_Name'],
                        'html_title'      => $v['BasicInfo']['Description'] ? $v['BasicInfo']['OfficialAccount_Name'] : '吃喝玩乐',
                        'title_about'     => $title_about,
                        'weixin_ID'       => $v['OfficialAccount_ID'],
                        'qq_ID'           => '1001001001',
                        'modular_id'      => 1,
                        'modular_name'    => '微信营销',
                        'theme_id'        => 1,
                        'theme_name'      => '公众号',
                        'filed_id'        => $filed_id,
                        'filed_name'      => Filed::whereFiledId($filed_id)->value('filed_name'),
                        'fans_num'        => $v['Estimated_Fans_Num'],
                        'avg_read_num'    => $v['Avg_Read_Num'],
                        'avg_like_num'    => $v['Avg_Like_Num'],
                        'avg_comment_num' => $v['Avg_Comment_Num'],
                        'avatar_url'      => $v['BasicInfo']['Avatar_Url'],
                        'qrcode_url'      => $v['BasicInfo']['Qrcode_Url'],
                        'region_id'       => 1,
                        'region_name'     => '全国',
                        'created_at'      => $time,
                        'updated_at'      => $time
                    ]);

                    GoodsPrice::create([
                        'goods_id'           => $goodsId,
                        'priceclassify_id'   => 1,
                        'priceclassify_name' => '单图文头条',
                        'price'              => 0
                    ]);

                    GoodsPrice::create([
                        'goods_id'           => $goodsId,
                        'priceclassify_id'   => 2,
                        'priceclassify_name' => '多图文头条',
                        'price'              => $v['Multi_Price_Top']
                    ]);

                    GoodsPrice::create([
                        'goods_id'           => $goodsId,
                        'priceclassify_id'   => 3,
                        'priceclassify_name' => '多图文第二条',
                        'price'              => $v['Multi_Price_UnTop_Second']
                    ]);

                    GoodsPrice::create([
                        'goods_id'           => $goodsId,
                        'priceclassify_id'   => 4,
                        'priceclassify_name' => '多图文3-n条',
                        'price'              => 0
                    ]);
                });
        }

    }

    // 判断是否含有emoji
    function have_special_char($str)
    {
        $length = mb_strlen($str);
        $array  = [];
        for ($i = 0; $i < $length; $i++) {
            $array[] = mb_substr($str, $i, 1, 'utf-8');
            if (strlen($array[$i]) >= 4) {
                return false;
            }
        }
        return true;
    }
}
