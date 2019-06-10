<?php


namespace App\Models\Weibo;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Weibo\GoodsWeiboPrice
 *
 * @property string $goods_id 商品id
 * @property int $priceclassify_id 价格种类id
 * @property string $priceclassify_name 价格种类名称
 * @property float $price 价格
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeiboPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeiboPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeiboPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeiboPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeiboPrice whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeiboPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeiboPrice wherePriceclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeiboPrice wherePriceclassifyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeiboPrice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoodsWeiboPrice extends Model
{
    protected $table = 'goods_weibo_price';

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

        return $query->pluck('goods_weibo_id');
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