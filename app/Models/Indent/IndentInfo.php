<?php


namespace App\Models\Indent;


use App\Models\Nb\Goods;
use App\Models\Up\Runwater;
use App\Models\Up\Wallet;
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
 * @property int $trade_status 交易状态 0=进行中 1=已完成 2=取消交易 3=已结算
 * @property int $pay_status 0=未付款 1=已付款
 * @property float $total_amount 商品最终金额
 * @property float $indent_amount 订单金额
 * @property float|null $pay_amount 付款金额
 * @property string|null $outer_trade_no 交易订单号（三方平台给出）
 * @property string|null $remark
 * @property string|null $pay_time 订单支付时间
 * @property string|null $create_time
 * @property int $delete_status 删除状态 0=未删除 1=删除
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereDeleteStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereIndentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereIndentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereIndentNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereOuterTradeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo wherePayAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo wherePayStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo wherePayTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereTradeStatus($value)
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
        '执行中取消订单' => 4,
        '卖方完成'    => 5,
        '全部完成'    => 6,
        '已结算' => 7
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
                    // 创建订单信息
                    $indentId = self::insertGetId([
                        'indent_num'    => createIndentNnm($key),
                        'buyer_id'      => $uid,
                        'total_amount'  => $dt['amount'],
                        'indent_amount' => $dt['amount'],
                        'create_time'   => $time
                    ]);

                    // 创建订单子项
                    foreach ($dt['indentGoods'] as $it) {
                        IndentItem::create([
                            'indent_id'          => $indentId,
                            'seller_id'          => $it['uid'],
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
    public static function checkIndentBelong($buyer_id)
    {
        if ($buyer_id != JWTAuth::user()->uid)
            throw new Exception('订单归属错误');

        return true;
    }

    // 检查订单状态
    public static function checkIndentStatus($status, $needStatus)
    {
        if($status != $needStatus)
            throw new Exception('订单状态非法');

        return true;
    }

    // 支付购买
    public static function pay($indentData)
    {
        DB::transaction(function () use ($indentData) {
            try {
                $time  = date('Y-m-d H:i:s');

                // 公共钱包资金增加
                $centerMoney = Wallet::whereUid(Wallet::CENTERID)->value('available_money') + $indentData->indent_amount;
                Wallet::whereUid(Wallet::CENTERID)->update([
                    'available_money' => $centerMoney,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock(Wallet::CENTERID, $centerMoney, $time)
                ]);

                // 卖家钱包资金扣除
                $money = Wallet::whereUid($indentData->buyer_id)->value('available_money') - $indentData->indent_amount;
                Wallet::whereUid($indentData->buyer_id)->update([
                    'available_money' => $money,
                    'time'            => $time,
                    'change_lock'     => createWalletChangeLock($indentData->buyer_id, $money, $time)
                ]);

                // 生成交易流水
                $key         = 'RUNWATERCOUNT' . date('Ymd'); // 单数key
                $runwaterNum = createRunwaterNum($key);
                Runwater::create([
                    'runwater_num' => $runwaterNum,
                    'form_uid'     => $indentData->buyer_id,
                    'to_uid'       => Wallet::CENTERID,
                    'indent_id'    => $indentData->indent_id,
                    'indent_num'   => $indentData->indent_num,
                    'type'         => Runwater::TYPE['交易'],
                    'direction'    => Runwater::DIRECTION['转出'],
                    'money'        => $indentData->indent_amount,
                    'status'       => Runwater::STATUS['成功']
                ]);
                Cache::increment($key);

                // 修改订单信息
                $indentData->status     = self::STATUS['已付款待接单'];
                $indentData->pay_amount = $indentData->indent_amount;
                $indentData->pay_time   = $time;
                $indentData->save();
            } catch (\Exception $e) {
                throw new Exception('操作失败');
            }
        });

        return true;
    }


}