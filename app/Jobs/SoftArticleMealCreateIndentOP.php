<?php


namespace App\Jobs;

use App\Models\Indent\IndentInfo;
use App\Models\Indent\IndentItem;
use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class SoftArticleMealCreateIndentOP implements ShouldQueue
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
                // 获取商品信息
                $goodsData = Goods::with(['one_goods_price' => function ($query) use ($goodsId) {
                    $goodsPriceId = GoodsPrice::whereGoodsId($goodsId)->value('goods_price_id');
                    $query->where('goods_price_id', $goodsPriceId);
                }])
                    ->where('goods_id', $goodsId)
                    ->first()
                    ->toArray();

                // 检查商品信息
                Goods::checkGoodsData($goodsData);

                $time = date('Y-m-d H:i:s');
                $key  = 'INDENTCOUNT' . date('Ymd'); // 订单数key

                // 创建订单信息
                $indent_num = createIndentNnm($key);
                $indentId   = IndentInfo::insertGetId([
                    'indent_num'        => $indent_num,
                    'buyer_id'          => User::GF,
                    'seller_id'         => $goodsData['uid'],
                    'salesman_id'       => null,
                    'total_amount'      => $goodsData['one_goods_price']['price'],
                    'indent_amount'     => $goodsData['one_goods_price']['price'],
                    'compensate_fee'    => 0,
                    'seller_income'     => $goodsData['one_goods_price']['floor_price'],
                    'bargaining_status' => IndentInfo::BARGAINING_STATUS['已完成'],
                    'create_time'       => $time,
                    'bind_indent_num'   => $indentNum
                ]);

                IndentItem::create([
                    'indent_id'          => $indentId,
                    'goods_id'           => $goodsData['goods_id'],
                    'goods_num'          => $goodsData['goods_num'],
                    'goods_title'        => $goodsData['title'],
                    'modular_name'       => $goodsData['modular_name'],
                    'theme_name'         => $goodsData['theme_name'],
                    'priceclassify_name' => $goodsData['one_goods_price']['priceclassify_name'],
                    'goods_price'        => $goodsData['one_goods_price']['price'],
                    'goods_amount'       => $goodsData['one_goods_price']['price'],
                    'create_time'        => $time
                ]);

                // 订单数自增
                Cache::increment($key);
            } catch (\Exception $e) {
                Log::error('【软文套餐创建订单】 报错: ' . $e->getMessage());
                continue;
            }
        }
    }
}
