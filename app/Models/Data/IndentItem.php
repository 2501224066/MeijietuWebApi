<?php


namespace App\Models\Data;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Data\IndentItem
 *
 * @property int $item_id
 * @property int $indent_id 订单id
 * @property int $goods_id
 * @property string|null $goods_num
 * @property string $goods_title
 * @property string $avatar_url 商品图像
 * @property string $qrcode_url 商品二维码
 * @property string $modular_name 模块
 * @property string $theme_name 主题
 * @property string $priceclassify_name 价格种类
 * @property float $goods_price 商品单价
 * @property int $goods_count 商品数量
 * @property float $goods_amount 商品总价
 * @property string $create_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereGoodsAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereGoodsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereGoodsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereGoodsPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereGoodsTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereIndentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereModularName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem wherePriceclassifyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereQrcodeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\IndentItem whereThemeName($value)
 * @mixin \Eloquent
 */
class IndentItem extends Model
{
    protected $table = 'data_indent_item';

    protected $primaryKey = 'item_id';

    protected $guarded = [];

    public $timestamps = false;
}