<?php


namespace App\Models\Indent;


use App\Models\Nb\Goods;
use App\Models\SystemSetting;
use App\Models\Tb\Modular;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\Indent\IndentInfo
 *
 * @property int $indent_id
 * @property string $indent_num 订单号
 * @property int $buyer_id 买家id
 * @property int $seller_id 卖家id
 * @property float $total_amount 商品最终金额
 * @property float $indent_amount 订单金额
 * @property float $compensate_fee 赔偿保证费
 * @property float|null $pay_amount 付款金额
 * @property string|null $pay_time 订单支付时间
 * @property int $status 交易状态 0=待付款 1=已付款待接单 2=待接单取消订单 3=执行中 4=执行中买家取消订单 5=执行中卖家取消订单 6=卖方完成 7=全部完成 8=已结算
 * @property string|null $create_time
 * @property string|null $refuse_cause 拒绝原因
 * @property string|null $demand_file 需求文档
 * @property string|null $prove_file 证明文档
 * @property int $delete_status 删除状态 0=未删除 1=删除
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereCompensateFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereDeleteStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereDemandFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereIndentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereIndentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereIndentNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo wherePayAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo wherePayTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereProveFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereRefuseCause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereTotalAmount($value)
 * @mixin \Eloquent
 */
class IndentInfo extends Model
{
    protected $table = 'indent_info';

    protected $primaryKey = 'indent_id';

    protected $guarded = [];

    public $timestamps = false;

    const STATUS = [
        '待付款'     => 0,
        '已付款待接单'  => 1,
        '待接单取消订单' => 2,
        '执行中'     => 3,
        '执行中买家取消订单' => 4,
        '执行中卖家取消订单' => 5,
        '卖方完成'    => 6,
        '全部完成'    => 7,
        '已结算'     => 8
    ];

    // 数据整理
    public static function dataSorting($info)
    {
        $data = [];

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

            // 根据不同卖家生成订单
            $seller_id                = $goodsData['uid'];
            $goodsData['goods_count'] = $it['goods_count'];
            if (isset($data[$seller_id]['amount'])) { // 订单总价
                $data[$seller_id]['amount'] += $goodsData['one_goods_price']['price'] * $goodsData['goods_count'];
            } else {
                $data[$seller_id]['amount'] = $goodsData['one_goods_price']['price'] * $goodsData['goods_count'];
            }
            $data[$seller_id]['modular_id'] = $goodsData['modular_id'];
            $data[$seller_id]['indentGoods'][] = $goodsData;
        }

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
                foreach ($data as $seller_id => $dt) {
                    $compensate_fee = floor($dt['amount'] * SystemSetting::whereSettingName('compensate_fee_ratio')->value('value'));

                    // 软文营销不需赔偿保证费
                    switch (Modular::whereModularId($dt['modular_id'])->value('tag')){
                        case Modular::TAG['软文营销']:
                            $compensate_fee = 0;
                            break;
                    }

                    // 创建订单信息
                    $indentId = self::insertGetId([
                        'indent_num'     => createIndentNnm($key),
                        'buyer_id'       => $uid,
                        'seller_id'      => $seller_id,
                        'total_amount'   => $dt['amount'],
                        'indent_amount'  => $dt['amount'],
                        'compensate_fee' => $compensate_fee,
                        'create_time'    => $time
                    ]);

                    // 创建订单子项
                    foreach ($dt['indentGoods'] as $it) {
                        IndentItem::create([
                            'indent_id'          => $indentId,
                            'goods_id'           => $it['goods_id'],
                            'goods_num'          => $it['goods_num'],
                            'goods_title'        => $it['title'],
                            'modular_name'       => $it['modular_name'],
                            'theme_name'         => $it['theme_name'],
                            'priceclassify_name' => $it['one_goods_price']['priceclassify_name'],
                            'goods_price'        => $it['one_goods_price']['price'],
                            'goods_count'        => $it['goods_count'],
                            'goods_amount'       => $it['one_goods_price']['price'] * $it['goods_count'],
                            'create_time'        => $time
                        ]);
                    }

                    // 订单数自增
                    Cache::increment($key);
                }
            } catch (\Exception $e) {
                throw new Exception('创建失败');
            }
        });

        return true;
    }

    // 检查订单归属
    public static function checkIndentBelong($uid)
    {
        if ($uid != JWTAuth::user()->uid)
            throw new Exception('订单不属于您');

        return true;
    }

    // 检查订单状态
    public static function checkIndentStatus($status, $needStatus)
    {
        if ($status != $needStatus)
            throw new Exception('订单状态非法');

        return true;
    }
}