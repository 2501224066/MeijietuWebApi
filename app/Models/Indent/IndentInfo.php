<?php


namespace App\Models\Indent;


use App\Models\Nb\Goods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class IndentInfo extends Model
{
    protected $table = 'indent_info';

    protected $primaryKey = 'indent_id';

    protected $guarded = [];

    public $timestamps = false;

    // 数据整理
    public static function dataSorting($info)
    {
        $amount = 0; // 商品总价

        foreach ($info as &$it) {
            // 验证数据完整性
            if (!($it['goods_id'] && $it['goods_price_id'] && $it['goods_count']))
                throw new Exception('数据错误');

            // 获取商品信息
            $goodsData = Goods::with(['one_goods_price' => function ($query) use ($it) {
                $query->where('goods_price_id', $it['goods_price_id']);
            }])
                ->where('goods_id', $it['goods_id'])
                ->first()
                ->toArray();

            // 检查商品信息
            self::checkGoodsData($goodsData);

            $it['goodsData'] = $goodsData;

            $amount += $goodsData['one_goods_price']['price'] * $it['goods_count']; // 商品单价 * 数量
        }
        $data['info']   = $info;
        $data['amount'] = $amount;

        return $data;
    }

    // 检查商品信息
    public static function checkGoodsData($goodsData)
    {
        if ($goodsData['status'] == Goods::STATUS['下架'])
            throw new Exception('含有已下架商品');

        if (!($goodsData && $goodsData['one_goods_price']))
            throw new Exception('未发现商品信息');

        if ($goodsData['one_goods_price']['price'] <= 0)
            throw new Exception('含有不出售商品');

        return true;
    }

    // 添加订单
    public static function add($data)
    {
        $uid  = JWTAuth::user()->uid;
        $time = date('Y-m-d H:i:s');
        $key  = 'INDENTCOUNT' . date('Ymd'); // 订单数key
        DB::transaction(function () use ($data, $uid, $time, $key) {
            try {
                // 创建订单信息
                $indentId = self::insertGetId([
                    'indent_num'    => createIndentNnm($key),
                    'buyer_id'      => $uid,
                    'total_amount'  => $data['amount'],
                    'indent_amount' => $data['amount'],
                    'create_time'   => $time
                ]);


                // 创建订单子项
                foreach ($data['info'] as $it) {
                    $re = IndentItem::create([
                        'indent_id'          => $indentId,
                        'seller_id'          => $it['goodsData']['uid'],
                        'goods_id'           => $it['goodsData']['goods_id'],
                        'goods_num'          => $it['goodsData']['goods_num'],
                        'goods_title'        => $it['goodsData']['title'],
                        'modular_name'       => $it['goodsData']['modular_name'],
                        'theme_name'         => $it['goodsData']['theme_name'],
                        'priceclassify_name' => $it['goodsData']['one_goods_price']['priceclassify_name'],
                        'goods_price'        => $it['goodsData']['one_goods_price']['price'],
                        'goods_count'        => $it['goods_count'],
                        'goods_amount'       => $it['goodsData']['one_goods_price']['price'] * $it['goods_count'],
                        'create_time'        => $time
                    ]);
                }

                // 订单数自增
                Cache::increment($key);
            } catch (\Exception $e) {
                throw new Exception('创建失败');
            }
        });

        return true;
    }
}