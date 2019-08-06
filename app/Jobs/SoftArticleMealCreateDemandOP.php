<?php


namespace App\Jobs;

use App\Models\Data\Demand;
use App\Models\Data\IndentInfo;
use App\Models\Data\Goods;
use App\Models\Data\GoodsPrice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SoftArticleMealCreateDemandOP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $indentData;

    protected $goodsIdArr;

    public function __construct($indentNum, $goodsIdArr)
    {
        $this->indentData = IndentInfo::whereIndentNum($indentNum)->first();
        $this->goodsIdArr = $goodsIdArr;
    }

    public function handle()
    {
        $indentId   = $this->indentData->indent_id;
        $word       = $this->indentData->demand_file;
        $goodsIdArr = $this->goodsIdArr;
        foreach ($goodsIdArr as $goodsId) {
            try {
                // 获取信息
                $goodsData = Goods::with(['one_goods_price' => function ($query) use ($goodsId) {
                    $query->where('goods_price_id', GoodsPrice::whereGoodsId($goodsId)->value('goods_price_id'));
                }])
                    ->where('goods_id', $goodsId)
                    ->first()
                    ->toArray();

                // 检查商品信息
                Goods::checkGoodsData($goodsData);

                // 创建需求
                $time = date('Y-m-d H:i:s');
                Demand::insertGetId([
                    'demand_num'      => createNum('DEMAND'),
                    'bind_indent_id' => $indentId,
                    'uid'            => $goodsData['uid'],
                    'title'          => $goodsData['title'],
                    'word'           => $word,
                    'price'          => $goodsData['one_goods_price']['floor_price'],
                    'created_at'     => $time,
                    'updated_at'     => $time,
                ]);
            } catch (\Exception $e) {
                Log::error('【需求】 软文套餐创建需求失败 ' . $e->getMessage());
                continue;
            }
        }
    }
}
