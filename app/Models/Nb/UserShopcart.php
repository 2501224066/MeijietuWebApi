<?php


namespace App\Models\Nb;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Nb\UserShopcart
 *
 * @property int $shopcart_id 购物车id
 * @property int $uid 用户id
 * @property int $goods_id 商品id
 * @property string $modular_type 模块类型
 * @property int $priceclassify_id 价格种类
 * @property string $priceclassify_name 价格种类
 * @property int $buy_number 购买数量
 * @property float $unit_price 单价
 * @property float $total_price 总价
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart whereBuyNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart whereModularType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart wherePriceclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart wherePriceclassifyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart whereShopcartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserShopcart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserShopcart extends Model
{
    protected $table = 'nb_user_shopcart';

    public $guarded = [];
}