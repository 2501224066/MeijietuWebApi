<?php


namespace App\Models\Indent;


use App\Models\Nb\Goods;
use App\Models\SystemSetting;
use App\Models\Tb\Modular;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property float $seller_income 卖家收入 默认=订单价格 *（1 - 服务费率）
 * @property int $bargaining_status 议价状态 0=未完成 1=已完成
 * @property int $status 交易状态 0=待付款 1=已付款待接单 2=待接单买家取消订单 3=卖家拒单  4=交易中 5=交易中买家取消订单 6=交易中卖家取消订单 7=卖方完成 8=全部完成 9=已结算
 * @property string|null $create_time
 * @property string|null $cancel_cause 取消原因
 * @property string|null $demand_file 需求文档
 * @property string|null $achievements_file 成果文档
 * @property int $delete_status 删除状态 0=未删除 1=删除
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Indent\IndentItem[] $indent_item
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereAchievementsFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereBargainingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereCancelCause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereCompensateFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereDeleteStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereDemandFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereIndentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereIndentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereIndentNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo wherePayAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo wherePayTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereSellerIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereTotalAmount($value)
 * @mixin \Eloquent
 * @property int $salesman_id 客服id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentInfo whereSalesmanId($value)
 */
class IndentInfo extends Model
{
    protected $table = 'indent_info';

    protected $primaryKey = 'indent_id';

    protected $guarded = [];

    public $timestamps = false;

    const BARGAINING_STATUS = [
        '未完成' => 0,
        '已完成' => 1
    ];

    const STATUS = [
        '待付款'       => 0,
        '已付款待接单'    => 1,
        '待接单买家取消订单' => 2,
        '卖家拒单'      => 3,
        '交易中'       => 4,
        '交易中买家取消订单' => 5,
        '交易中卖家取消订单' => 6,
        '卖方完成'      => 7,
        '全部完成'      => 8,
        '已结算'       => 9
    ];

    public function indent_item(): HasMany
    {
        return $this->hasMany(IndentItem::class, 'indent_id', 'indent_id');
    }

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
            Goods::checkGoodsData($goodsData);

            // 根据不同卖家生成订单
            $seller_id                = $goodsData['uid'];
            $goodsData['goods_count'] = $it['goods_count'];

            // 订单总价
            if (isset($data[$seller_id]['amount'])) {
                $data[$seller_id]['amount'] += $goodsData['one_goods_price']['price'] * $goodsData['goods_count'];
            } else {
                $data[$seller_id]['amount'] = $goodsData['one_goods_price']['price'] * $goodsData['goods_count'];
            }

            // 底价总价
            if (isset($data[$seller_id]['floor_amount'])) {
                $data[$seller_id]['floor_amount'] += $goodsData['one_goods_price']['floor_price'] * $goodsData['goods_count'];
            } else {
                $data[$seller_id]['floor_amount'] = $goodsData['one_goods_price']['floor_price'] * $goodsData['goods_count'];
            }

            $data[$seller_id]['modular_id']    = $goodsData['modular_id'];
            $data[$seller_id]['indentGoods'][] = $goodsData;
        }

        return $data;
    }

    // 添加订单
    public static function add($data)
    {
        $indent_num = "";

        $uid         = JWTAuth::user()->uid;
        $salesman_id = JWTAuth::user()->salesman_id;
        $time        = date('Y-m-d H:i:s');
        $key         = 'INDENTCOUNT' . date('Ymd'); // 订单数key
        DB::transaction(function () use ($data, $uid, $salesman_id, $time, $key, &$indent_mum) {
            try {
                foreach ($data as $seller_id => $dt) {
                    // 赔偿保证费
                    $compensate_fee = floor($dt['amount'] * SystemSetting::whereSettingName('compensate_fee_ratio')->value('value'));

                    // 议价状态
                    $bargaining_status = IndentInfo::BARGAINING_STATUS['未完成'];

                    // 部分模块无需赔偿保证费
                    switch (Modular::whereModularId($dt['modular_id'])->value('tag')) {
                        case Modular::TAG['软文营销']:
                            $compensate_fee = 0;
                            break;

                        case Modular::TAG['自身业务']:
                            $compensate_fee = 0;
                            break;
                    }

                    // 不同模式下卖家收入
                    switch (Modular::whereModularId($dt['modular_id'])->value('settlement_type')) {
                        // 标准模式下卖家收入默认为 订单价格*（1-服务费率） 仍需议价
                        case Modular::SETTLEMENT_TYPE['标准模式']:
                            $seller_income = floor($dt['amount'] * (1 - SystemSetting::whereSettingName('service_fee_ratio')->value('value')));
                            break;

                        // 软文模式下卖家收入为商品底价 无需议价
                        case Modular::SETTLEMENT_TYPE['软文模式']:
                            $seller_income     = $dt['floor_amount'];
                            $bargaining_status = IndentInfo::BARGAINING_STATUS['已完成'];
                            break;

                        // 自身模式下卖家(平台自己)收入订单价格 无需议价
                        case Modular::SETTLEMENT_TYPE['自身模式']:
                            $seller_income     = $dt['amount'];
                            $bargaining_status = IndentInfo::BARGAINING_STATUS['已完成'];
                            break;
                    }


                    // 创建订单信息
                    $indent_mum = createIndentNnm($key);
                    $indentId   = self::insertGetId([
                        'indent_num'        => $indent_mum,
                        'buyer_id'          => $uid,
                        'seller_id'         => $seller_id,
                        'salesman_id'       => $salesman_id,
                        'total_amount'      => $dt['amount'],
                        'indent_amount'     => $dt['amount'],
                        'compensate_fee'    => $compensate_fee,
                        'seller_income'     => $seller_income,
                        'bargaining_status' => $bargaining_status,
                        'create_time'       => $time
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
                throw new Exception('操作失败');
            }
        });

        return $indent_mum;
    }

    // 检查订单归属
    public static function checkIndentBelong($uidArr)
    {
        if (!in_array(JWTAuth::user()->uid, $uidArr))
            throw new Exception('订单不属于您');

        return true;
    }

    // 检查议价状态
    public static function checkSaceBuyerIncomeStatus($bargaining_status, $needStatus)
    {
        if ($bargaining_status != $needStatus)
            throw new Exception('议价未完成，请联系客服');

        return true;
    }

    // 自己订单
    public static function getSelfIndent()
    {
        $user = JWTAuth::user();

        $query = IndentInfo::whereRaw('buyer_id = ? or seller_id = ?', [$user->uid, $user->uid])
            ->with('indent_item')
            ->orderBy('create_time', 'ASC');

        // 媒体主显示 已付款待接单 的状态节点后订单
        if ($user->identity == User::IDENTIDY['媒体主']) {
            $query->where('status', '>=', self::STATUS['已付款待接单']);
        }

        return $query->paginate();

    }
}