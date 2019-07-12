<?php


namespace App\Models\Nb;


use App\Models\Tb\Theme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;


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
        // 价格种类为空直接跳出
        if (!$request->priceclassify_id)
            return false;

        if ($request->priceclassify_id != null)
            $query = self::where('priceclassify_id', $request->priceclassify_id);

        // 排除所有价格为0的商品降低结构集大小
        $query->where('price','!=', 0);

        if ($request->pricelevel_min != null)
            $query->where('price', '>=', $request->pricelevel_min);

        if ($request->pricelevel_max != null)
            $query->where('price', '<', $request->pricelevel_max);

        return $query->groupBy('goods_id')->pluck('goods_id');
    }

    // 检查价格种类完整性
    public static function checkPriceclassify($themeId, $priceArr)
    {
        $arr = DB::table('tb_theme_priceclassify')->where('theme_id', $themeId)->pluck('priceclassify_id')->toArray();
        asort($arr);
        $arr = array_values($arr);

        $inputArr = array_keys($priceArr);
        asort($inputArr);
        $inputArr = array_values($inputArr);

        if ($arr != $inputArr)
            throw new Exception('价格信息不完整');

        return true;
    }

    // 检测价格数据合法性
    public static function checkPrice($priceArr)
    {
        foreach ($priceArr as $price) {
            if ($price < 0)
                throw new Exception('价格不得小于零');
        }

        return true;
    }

}