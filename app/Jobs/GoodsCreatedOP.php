<?php

namespace App\Jobs;

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
                Goods::addWeiXinBasicData($goodsId, $arr['weixin_ID']);
                break;

            // 微博基础数据
            case Modular::TAG['微博营销']:
                Goods::addWeiBoBasicData($goodsId, $arr['link']);
                break;
        }

        // 消除初始商品
        Goods::delSelfCreateGoods($goodsId);
    }


}
