<?php

namespace App\Jobs;

use App\Models\Attr\Theme;
use App\Models\Data\Goods;
use App\Models\Attr\Modular;
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

    public function handle()
    {
        $goodsId = $this->goodsId;
        $arr     = $this->arr;

        // 添加基础数据
        switch (Modular::whereModularId($arr['modular_id'])->value('tag')) {
            // 微信基础数据
            case Modular::TAG['微信营销']:
                Goods::addWeiXinBasicData($goodsId, $arr['weixin_ID']);
                Goods::delSelfCreateGoods($goodsId, 1); // 消除初始商品
                break;

            // 微博基础数据
            case Modular::TAG['微博营销']:
                Goods::addWeiBoBasicData($goodsId, $arr['link']);
                Goods::delSelfCreateGoods($goodsId, 2); // 消除初始商品
                break;

            // 小红书基础数据
            case Modular::TAG['视频营销']:
                if (($arr['theme_name'] == '短视频')
                    && ($arr['platform_name'] == '小红书')) {
                    Goods::addXiaoHongShuBasicData($goodsId, $arr['room_ID']);
                    Goods::delSelfCreateGoods($goodsId, 3); // 消除初始商品
                }
                break;

        }
    }


}
