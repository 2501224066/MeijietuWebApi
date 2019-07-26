<?php


namespace App\Jobs;

use App\Models\Dt\Demand;
use App\Models\Indent\IndentInfo;
use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SoftArticleMealCreateDemandOP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $indentNum;

    protected $goodsIdArr;

    public function __construct($indentNum, $goodsIdArr)
    {
        $this->indentNum  = $indentNum;
        $this->goodsIdArr = $goodsIdArr;
    }

    public function handle()
    {
        $indentNum  = $this->indentNum;
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
                    'demand_id'       => createNum('DEMAND'),
                    'bind_indent_num' => $indentNum,
                    'title'           => $goodsData['title'],
                    'word'            => IndentInfo::whereIndentNum($indentNum)->value('demand_file'),
                    'price'           => $goodsData['one_goods_price']['price'],
                    'created_at'      => $time,
                    'updated_at'      => $time,
                ]);
            } catch (\Exception $e) {
                Log::error('软文套餐创建需求失败，' . $e->getMessage());
                continue;
            }
        }
    }
}
