<?php


namespace App\Models\Selfmeida;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Selfmeida\GoodsSelfmeidaPrice
 *
 * @property string $goods_id
 * @property int $priceclassify_id
 * @property string $priceclassify_name
 * @property string $tag 标记
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\GoodsSelfmeidaPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\GoodsSelfmeidaPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\GoodsSelfmeidaPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\GoodsSelfmeidaPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\GoodsSelfmeidaPrice whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\GoodsSelfmeidaPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\GoodsSelfmeidaPrice wherePriceclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\GoodsSelfmeidaPrice wherePriceclassifyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\GoodsSelfmeidaPrice whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\GoodsSelfmeidaPrice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoodsSelfmeidaPrice extends Model
{
    protected $table = 'goods_selfmedia_price';

    public $guarded = [];


    // 筛选价格
    public static function screenPrice($data)
    {
        if ( ! $data->priceclassify_id)
            return false;

        $query =  self::wherePriceclassifyId($data->priceclassify_id);

        if ($data->pricelevel_min)
            $query->where('price', '>', $data->pricelevel_min);

        if ($data->pricelevel_max)
            $query->where('price', '<=', $data->pricelevel_max);

        return $query->pluck('goods_id');
    }

    // 插入价格信息
    public static function withPriceInfo($data)
    {
        foreach ($data as &$value)
        {
            $value->price_info = self::whereGoodsId($value->goods_id)->get();
        }

        return $data;
    }
}