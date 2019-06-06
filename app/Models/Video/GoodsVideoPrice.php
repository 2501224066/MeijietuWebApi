<?php


namespace App\Models\Video;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Video\GoodsVideoPrice
 *
 * @property string $goods_video_id
 * @property int $priceclassify_id
 * @property string $priceclassify_name
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideoPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideoPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideoPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideoPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideoPrice whereGoodsVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideoPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideoPrice wherePriceclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideoPrice wherePriceclassifyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideoPrice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoodsVideoPrice extends Model
{
    protected $table = 'goods_video_price';

    public $guarded = [];


    // 筛选价格
    public static function screenPrice($data)
    {
        if ( ! ($data->pricelevel_min || $data->pricelevel_max))
            return false;

        $query =  self::wherePriceclassifyId($data->priceclassify_id);

        if ($data->pricelevel_min)
            $query->where('price', '>', $data->pricelevel_min);

        if ($data->pricelevel_max)
            $query->where('price', '<=', $data->pricelevel_max);

        return $query->pluck('goods_video_id');
    }

    // 插入价格信息
    public static function withPriceInfo($data)
    {
        foreach ($data as &$value)
        {
            $value->price_info = self::whereGoodsVideoId($value->goods_video_id)->get();
        }

        return $data;
    }
}