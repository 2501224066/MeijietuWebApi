<?php

namespace App\Jobs;

use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;
use App\Models\Tb\Modular;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class GoodsCreatedOP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $goodsId;

    protected $arr;

    public function __construct($goodsId, $arr)
    {
        $this->goodsId = $goodsId;
        $this->arr     = $arr;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $goodsId = $this->goodsId;
        $arr     = $this->arr;

        // 添加基础数据
        switch (Modular::whereModularId($arr['modular_id'])->value('tag')) {
            // 微信基础数据
            case Modular::TAG['微信营销']:
                $this->addWeiXinBasicData($goodsId, $arr['weixin_ID']);
                break;

            // 微博基础数据
            case Modular::TAG['微博营销']:
                $this->addWeiBoBasicData($goodsId, $arr['link']);
                break;
        }

        // 消除制造商品
        $this->delZZGoods($goodsId);
    }

    // 添加微信基础数据
    public function addWeiXinBasicData($goodsId, $weixin_ID)
    {
        // 查询自库数据
        $re = DB::connection('weixin_mongodb')
            ->collection('WeiXin_OfficialAccount_Analysis')
            ->where('OfficialAccount_ID', $weixin_ID)
            ->first();

        // 存入商品表中
        if ($re)
            DB::table('nb_goods')
                ->where('goods_id', $goodsId)
                ->update([
                    'avg_read_num'    => $re['Avg_Read_Num'],
                    'avg_like_num'    => $re['Avg_Like_Num'],
                    'avg_comment_num' => $re['Avg_Comment_Num'],
                    'avatar_url'      => $re['BasicInfo']['Avatar_Url'],
                    'qrcode_url'      => $re['BasicInfo']['Qrcode_Url']]);

    }

    // 添加微博基础数据
    public function addWeiBoBasicData($goodsId, $link)
    {
        // 截取链接最后数组ID
        if (strpos($link, '?')) {
            $arr = explode('/', substr($link, 0, strpos($link, '?')));
        } else {
            $arr = explode('/', $link);
        }
        $id = end($arr);

        // 查询自库数据
        $re = DB::connection('weibo_mongodb')
            ->collection('WeiBo_Analysis')
            ->where('WeiBo_Uid', $id)
            ->first();

        // 存入商品表中
        if ($re)
            DB::table('nb_goods')
                ->where('goods_id', $goodsId)
                ->update([
                    'avg_like_num'    => $re['Avg_Like_Num_Last10'],
                    'avg_comment_num' => $re['Avg_Comment_Num_Last10'],
                    'avg_retweet_num' => $re['Avg_Retweet_Num_Last10'],
                    'avatar_url'      => $re['BasicInfo']['Avatar_Url'],
                    'fans_num'        => $re['BasicInfo']['Fans_Num']
                ]);

    }

    /*
     *  消除制造商品[微信公众号][微博]
     *  初始创造的一批商品,当用户录入商品后，判断初始商品中是否有重复的，有则删除初始商品
     */
    public static function delZZGoods($goodsId)
    {
        // 微信公众号
        $goods = Goods::whereGoodsId($goodsId)->first();
        if ($goods->weixin_ID) {
            $arr = Goods::whereUid(0)
                ->where('filed_name', '公众号')
                ->where('weixin_ID', $goods->weixin_ID)
                ->pluck('goods_id');
            print_r($arr);
        }

        // 微博
        if ($goods->link) {
            $arr = Goods::whereUid(0)
                ->where('modular_name', '微博营销')
                ->where('link', $goods->link)
                ->pluck('goods_id');
        }

        /*foreach ($arr as $goods_id) {
            Goods::whereGoodsId($goods_id)->delete();
            GoodsPrice::whereGoodsId($goods_id)->delete();
        }*/
    }
}
