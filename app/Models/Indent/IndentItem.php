<?php


namespace App\Models\Indent;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Indent\IndentItem
 *
 * @property int $item_id
 * @property int $indent_id 订单id
 * @property int $seller_id 卖家id
 * @property int $goods_id
 * @property string|null $goods_num
 * @property string $goods_title
 * @property string $modular_name 模块
 * @property string $theme_name 主题
 * @property string $priceclassify_name 价格种类
 * @property float $goods_price 商品单价
 * @property int $goods_count 商品数量
 * @property float $goods_amount 商品总价
 * @property string $create_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem whereGoodsAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem whereGoodsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem whereGoodsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem whereGoodsPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem whereGoodsTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem whereIndentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem whereModularName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem wherePriceclassifyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Indent\IndentItem whereThemeName($value)
 * @mixin \Eloquent
 */
class IndentItem extends Model
{
    protected $table = 'indent_item';

    protected $primaryKey = 'item_id';

    protected $guarded = [];

    public $timestamps = false;
}