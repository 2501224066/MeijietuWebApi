<?php

namespace App\Console\Commands;

use App\Models\Data\Goods;
use App\Models\Data\GoodsPrice;
use App\Models\Attr\Filed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class weiXinGoodsRenew extends Command
{

    protected $signature = 'renew:weixin-goods {num}';


    protected $description = '更新初始微信商品数据';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $start = $this->argument('num');
        $end   = $start + 2000;
        while ($start <= $end) {
            echo $start;
            $start++;

            $v = DB::connection('weixin_mongodb')
                ->collection('WeiXin_OfficialAccount_Analysis')
                ->offset(($start - 1))
                ->limit(1)
                ->first();
            // 未查到退出循环
            if (!$v) {
                echo " NULL\n";
                continue;
            }


            $goods = Goods::where('weixin_ID', $v['OfficialAccount_ID'])->first(); // 唯一标识
            // 存在即更新数据
            if ($goods) {
                echo " " . $goods->goods_id . " renew\n";
                $goods->fans_num        = $v['Estimated_Fans_Num'];
                $goods->avg_read_num    = $v['Avg_Read_Num_Top'];
                $goods->max_read_num    = $v['Max_Read_Num_Top'];
                $goods->avg_like_num    = $v['Avg_Like_Num_Top'];
                $goods->max_like_num    = $v['Max_Like_Num_Top'];
                $goods->avg_comment_num = $v['Avg_Comment_Num_Top'];
                $goods->max_comment_num = $v['Max_Comment_Num_Top'];
                $goods->save();
            } else {
                // 不存在即创建
                echo " insert\n";
                DB::transaction(function () use ($v) {

                    $time = date('Y-m-d H:i:s');

                    $filed_id = mt_rand(1, 13);
                    $goodsId  = Goods::insertGetId([
                        'goods_num'       => createNum('GOODS'),
                        'title'           => $v['BasicInfo']['OfficialAccount_Name'],
                        'html_title'      => $v['BasicInfo']['OfficialAccount_Name'],
                        'title_about'     => $v['BasicInfo']['Description'],
                        'weixin_ID'       => $v['OfficialAccount_ID'],
                        'qq_ID'           => '2501001001',
                        'modular_id'      => 1,
                        'modular_name'    => '微信营销',
                        'theme_id'        => 1,
                        'theme_name'      => '公众号',
                        'filed_id'        => $filed_id,
                        'filed_name'      => Filed::whereFiledId($filed_id)->value('filed_name'),
                        'fans_num'        => $v['Estimated_Fans_Num'],
                        'avg_read_num'    => $v['Avg_Read_Num_Top'],
                        'max_read_num'    => $v['Max_Read_Num_Top'],
                        'avg_like_num'    => $v['Avg_Like_Num_Top'],
                        'max_like_num'    => $v['Max_Like_Num_Top'],
                        'avg_comment_num' => $v['Avg_Comment_Num_Top'],
                        'max_comment_num' => $v['Max_Comment_Num_Top'],
                        'avatar_url'      => $v['BasicInfo']['Avatar_Url'],
                        'qrcode_url'      => $v['BasicInfo']['Qrcode_Url'],
                        'region_id'       => 1,
                        'reserve_status'  => 0,
                        'region_name'     => '全国',
                        'verify_status'   => 2,
                        'status'          => 1,
                        'created_at'      => $time,
                        'updated_at'      => $time
                    ]);

                    GoodsPrice::create([
                        'goods_id'           => $goodsId,
                        'priceclassify_id'   => 1,
                        'priceclassify_name' => '单图文头条',
                        'price'              => $v['Multi_Price_Top']
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
                        'price'              => $v['Multi_Price_UnTop_Second']
                    ]);

                    // 头像转到oss
                    try {
                        $imgContent = file_get_contents($v['BasicInfo']['Avatar_Url']);
                        $path       = "images/head_portrait/" . str_random(30) . ".jpg";
                        echo " " . $path . "\n";
                        Storage::put($path, $imgContent);
                        Goods::whereGoodsId($goodsId)->update([
                            'avatar_url' => $path,
                            'qrcode_url' => 'https://open.weixin.qq.com/qr/code?username=' . $v['OfficialAccount_ID'],
                        ]);
                    } catch (\Exception $e) {
                        echo " default" . "\n";
                        Goods::whereGoodsId($goodsId)->update([
                            'avatar_url' => 'images/head_portrait/teT9lkA17FB2XOq7kXFwnTozLSlwm8.jpg',
                            'qrcode_url' => 'https://open.weixin.qq.com/qr/code?username=' . $v['OfficialAccount_ID'],
                        ]);
                    }
                });
            }
        }

    }
}
