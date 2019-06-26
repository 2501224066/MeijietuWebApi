<?php


namespace App\Models\Nb;


use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Nb\GoodsPrice
 *
 * @property int $goods_price_id
 * @property int $goods_id
 * @property int $priceclassify_id
 * @property string $priceclassify_name 价格种类
 * @property float $floor_price 低价(软文模式使用)
 * @property float $price 真实价格
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\GoodsPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\GoodsPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\GoodsPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\GoodsPrice whereFloorPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\GoodsPrice whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\GoodsPrice whereGoodsPriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\GoodsPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\GoodsPrice wherePriceclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\GoodsPrice wherePriceclassifyName($value)
 * @mixin \Eloquent
 */
class GoodsPrice extends Model
{
    protected $table = 'nb_goods_price';

    protected $guarded = [];

    public $timestamps = false;

    // 筛选价格
    public static function screePrice($request)
    {
        if( ! $request->has('priceclassify_id'))
            return false;

        if ($request->has('priceclassify_id'))
            $query = self::where('priceclassify_id', $request->priceclassify_id);

        if ($request->has('pricelevel_min'))
            $query->where('price', '>=', $request->pricelevel_min);

        if ($request->has('pricelevel_max'))
            $query->where('price', '<', $request->pricelevel_max);

        return $query->groupBy('goods_id')->pluck('goods_id');
    }
}