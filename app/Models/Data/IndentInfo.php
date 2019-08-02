<?php


namespace App\Models\Data;


use App\Models\Data\Goods;
use App\Models\System\Setting;
use App\Models\Attr\Modular;
use App\Models\User;
use App\Server\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\Data\IndentInfo
 *
 * @property int $indent_id
 * @property string $indent_num 订单号
 * @property int $buyer_id 买家id
 * @property int $seller_id 卖家id
 * @property int|null $salesman_id 客服id
 * @property float $total_amount 商品最终金额
 * @property float $indent_amount 订单金额
 * @property float $compensate_fee 赔偿保证费
 * @property float|null $pay_amount 付款金额
 * @property string|null $pay_time 订单支付时间
 * @property float $seller_income 卖家收入 默认=订单价格 *（1 - 服务费率）
 * @property float $bargaining_reduce 议价节省 客服议价价差
 * @property int $bargaining_status 议价状态 0=未完成 1=已完成
 * @property int $status 交易状态 0=待付款 1=已付款待接单 2=待接单买家取消订单 3=卖家拒单  4=交易中 5=交易中买家取消订单 6=交易中卖家取消订单 7=卖方完成 8=全部完成 9=已结算
 * @property string|null $create_time
 * @property string|null $cancel_cause 取消原因
 * @property string|null $demand_file 需求文档
 * @property string|null $achievements_file 成果文档
 * @property int $delete_status 删除状态 0=未删除 1=删除
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Data\IndentItem[] $indent_item
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereAchievementsFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereBargainingReduce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereBargainingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereCancelCause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereCompensateFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereDeleteStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereDemandFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereIndentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereIndentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereIndentNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo wherePayAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo wherePayTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereSalesmanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereSellerIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentInfo whereTotalAmount($value)
 * @mixin \Eloquent
 */
class IndentInfo extends Model
{
    protected $table = 'data_indent_info';

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

    const DELETE_STATUS = [
        '未删除' => 0,
        '已删除' => 1
    ];

    // 无需赔偿保证费模块
    const NO_COMPENSATE_FEE_MODULAR_TAG = [
        Modular::TAG['软文营销'],
        Modular::TAG['自身业务']
    ];

    public function indent_item(): HasOne
    {
        return $this->hasOne(IndentItem::class, 'indent_id', 'indent_id');
    }

    /**
     * 数据整理
     *  1.一个商品一个订单
     * @param array $input 接受数据
     * @throws \Throwable
     */
    public static function dataSorting($input)
    {
        foreach ($input as &$in) {
            // 验证数据完整性
            if (!($in['goods_id'] && $in['goods_price_id'] && $in['goods_count']))
                throw new Exception('数据错误');

            // 获取商品信息
            $goodsData = Goods::with(['one_goods_price' => function ($query) use ($in) {
                $query->where('goods_price_id', $in['goods_price_id']);
            }])
                ->where('goods_id', $in['goods_id'])
                ->first()
                ->toArray();

            // 检查商品信息
            Goods::checkGoodsData($goodsData);

            // 创建订单
            self::createIndent($goodsData, $in['goods_count']);
        }
    }

    /**
     * 创建订单
     * @param array $goodsData 商品数据
     * @param int $goodsCount 购买数量
     * @throws \Throwable
     */
    public static function createIndent($goodsData, $goodsCount)
    {
        $indent_num = "";
        DB::transaction(function () use ($goodsData, $goodsCount, &$indent_num) {
            try {
                // 订单价格
                $indentPrice = $goodsData['one_goods_price']['price'];
                // 订单底价
                $indentFloorPrice = $goodsData['one_goods_price']['floor_price'];
                // 模块id
                $modularId = $goodsData['modular_id'];
                // 赔偿保证费
                $compensateFee = self::countCompensateFee($indentPrice, $modularId);
                // 卖家收入与议价状态
                $sellerIncomeAndBargainingStatus = self::sellerIncomeAndBargainingStatusForType($indentPrice, $modularId, $indentFloorPrice);
                // 买家
                $buyer_id = JWTAuth::user()->uid;
                // 卖家
                $seller_id = $goodsData['uid'];
                // 客服
                $salesman_id = JWTAuth::user()->salesman_id;
                // 时间
                $time = date('Y-m-d H:i:s');
                // 订单号
                $indent_num = createNum('INDENT');

                // 创建订单信息
                $indentId = self::insertGetId([
                    'indent_num'        => $indent_num,
                    'buyer_id'          => $buyer_id,
                    'seller_id'         => $seller_id,
                    'salesman_id'       => $salesman_id,
                    'total_amount'      => $indentPrice,
                    'indent_amount'     => $indentPrice,
                    'compensate_fee'    => $compensateFee,
                    'seller_income'     => $sellerIncomeAndBargainingStatus['sellerIncome'],
                    'bargaining_status' => $sellerIncomeAndBargainingStatus['bargainingStatus'],
                    'create_time'       => $time
                ]);

                // 创建订单子项
                IndentItem::create([
                    'indent_id'          => $indentId,
                    'goods_id'           => $goodsData['goods_id'],
                    'goods_num'          => $goodsData['goods_num'],
                    'goods_title'        => $goodsData['title'],
                    'modular_name'       => $goodsData['modular_name'],
                    'theme_name'         => $goodsData['theme_name'],
                    'priceclassify_name' => $goodsData['one_goods_price']['priceclassify_name'],
                    'goods_price'        => $goodsData['one_goods_price']['price'],
                    'goods_count'        => $goodsCount,
                    'goods_amount'       => $goodsData['one_goods_price']['price'] * $goodsCount,
                    'create_time'        => $time
                ]);
            } catch (\Exception $e) {
                throw new Exception('操作失败');
            }
        });

        // 发送短信
        Transaction::sms($indent_num, $goodsData['uid'], '买家选中您的商品创建了一笔订单，请联系客服');
    }

    /**
     * 计算赔偿保证费
     *  1.部分模块无需赔偿保证费
     * @param float $indentPrice 订单价格
     * @param int $modularId 模块id
     * @return float
     */
    public static function countCompensateFee($indentPrice, $modularId)
    {
        // 赔偿保证费率
        $compensateFeeRatio = Setting::whereSettingName('compensate_fee_ratio')->value('value');
        // 赔偿保证费
        $compensateFee = floor($indentPrice * $compensateFeeRatio);
        // 部分模块无需赔偿保证费
        $tag = Modular::whereModularId($modularId)->value('tag');
        if (in_array($tag, self::NO_COMPENSATE_FEE_MODULAR_TAG))
            $compensateFee = 0;

        return $compensateFee;
    }

    /**
     * 不同模式下卖家收入与议价状态
     * @param float $indentPrice 订单价格
     * @param int $modularId 模块id
     * @param float $indentFloorPrice 订单底价
     * @return array
     */
    public static function sellerIncomeAndBargainingStatusForType($indentPrice, $modularId, $indentFloorPrice)
    {
        // 卖家收入模式
        $type = Modular::whereModularId($modularId)->value('settlement_type');
        // 服务费率
        $serviceFeeRatio = Setting::whereSettingName('service_fee_ratio')->value('value');
        // 议价状态
        $bargainingStatus = IndentInfo::BARGAINING_STATUS['未完成'];

        switch ($type) {
            // 标准模式下卖家收入默认为 订单价格*（1-服务费率） 仍需议价
            case Modular::SETTLEMENT_TYPE['标准模式']:
                $sellerIncome = floor($indentPrice * $serviceFeeRatio);
                break;

            // 软文模式下卖家收入为商品底价 无需议价
            case Modular::SETTLEMENT_TYPE['软文模式']:
                $sellerIncome     = $indentFloorPrice;
                $bargainingStatus = IndentInfo::BARGAINING_STATUS['已完成'];
                break;

            // 自身模式下卖家(平台自己)收入订单价格 无需议价
            case Modular::SETTLEMENT_TYPE['自身模式']:
                $sellerIncome     = $indentPrice;
                $bargainingStatus = IndentInfo::BARGAINING_STATUS['已完成'];
                break;
        }

        return ['sellerIncome' => $sellerIncome, 'bargainingStatus' => $bargainingStatus];
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

        $query = IndentInfo::whereDeleteStatus(self::DELETE_STATUS['未删除'])
            ->whereRaw('buyer_id = ? or seller_id = ?', [$user->uid, $user->uid])
            ->with('indent_item')
            ->orderBy('create_time', 'ASC');

        // 媒体主显示 已付款待接单 的状态节点后订单
        if ($user->identity == User::IDENTIDY['媒体主']) {
            $query->where('status', '>=', self::STATUS['已付款待接单']);
        }

        return $query->paginate();
    }

    // 修改订单信息
    public static function updateIndent($indentData, $status, $pay_amount = null, $cancel_cause = null)
    {
        if ($status != null)
            $indentData->status = $status;

        if ($pay_amount != null) {
            $indentData->pay_amount = $pay_amount;
            $indentData->pay_time   = date('Y-m-d H:i:s');
        }

        if ($cancel_cause != null)
            $indentData->cancel_cause = $cancel_cause;

        if (!$indentData->save()) {
            throw new Exception('订单修改失败');
        }
    }
}