<?php


namespace App\Models\Video;


use Illuminate\Database\Eloquent\Model;

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