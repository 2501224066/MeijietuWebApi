<?php


namespace App\Models;


use App\Service\ModularData;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * App\Models\UserShopcart
 *
 * @property int $shopcart_id 购物车id
 * @property int $uid 用户id
 * @property int $goods_id 商品id
 * @property string $modular_type 模块类型
 * @property int $priceclassify_id 价格种类
 * @property string $priceclassify_name 价格种类
 * @property float $price 价格
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserShopcart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserShopcart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserShopcart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserShopcart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserShopcart whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserShopcart whereModularType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserShopcart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserShopcart wherePriceclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserShopcart wherePriceclassifyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserShopcart whereShopcartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserShopcart whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserShopcart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserShopcart extends Model
{
    protected $table = 'user_shopcart';

    public $guarded = [];

    // 判断商品是否已经加入
    public static function checkShopcartHas($data)
    {
        $count = self::whereModularType($data->modular_type)
            ->where('goods_id', $data->goods_id)
            ->count();
        if ($count)
            throw new Exception('此商品已加入购物车');

        return true;
    }

    // 检查商品价格信息
    public static function checkGoodsPrice($data)
    {
        $truePrice = ModularData::modularTypeToGetGoodsPriceTableClass($data->modular_type)
            ->where('goods_id', $data->goods_id)
            ->where('priceclassify_id', $data->priceclassify_id)
            ->value('price');
        if (!$truePrice)
            throw new Exception('商品信息有误');

        if ($truePrice != $data->price)
            throw new Exception('商品价格有误');

        if ($data->price <= 0)
            throw new Exception('商品此类别不出售');

        return true;
    }

    // 加入
    public static function add($data)
    {
        $re = self::create([
            'uid'                => JWTAuth::user()->uid,
            'goods_id'           => htmlspecialchars($data->goods_id),
            'modular_type'       => htmlspecialchars($data->modular_type),
            'priceclassify_id'   => htmlspecialchars($data->priceclassify_id),
            'priceclassify_name' => ModularData::modularTypeToGetPriceclassifyTableClass($data->modular_type)->where('priceclassify_id', $data->priceclassify_id)->value('priceclassify_name'),
            'unit_price'         => htmlspecialchars($data->price),
            'total_price'        => htmlspecialchars($data->price),
        ]);
        if (!$re)
            throw new Exception('加入购物车失败');

        return true;
    }

    // 删除
    public static function del($idArr)
    {
        foreach ($idArr as $id) {
            $re = self::whereShopcartId($id)->delete();
            if (!$re)
                throw new Exception('操作失败');
        }

        return true;
    }

    // 补充信息
    public static function withInfo($goods)
    {
        foreach ($goods as &$v) {
            // 模块类型名称
            $v->modular_type_name = type('MODULAR_TYPE')[$v->modular_type];
            // 商品信息
            $info                 = ModularData::modularTypeToGetGoodsTableClass($v->modular_type)::where('goods_id', $v->goods_id)->first();
            // 商品价格信息
            $price_info           = ModularData::modularTypeToGetGoodsPriceTableClass($v->modular_type)::where('goods_id', $v->goods_id)->get();
            if ($info) {
                $v->goods_title = $info->goods_title;
                $v->theme_name  = $info->theme_name;
                $v->filed_name  = $info->filed_name;
                $v->goods_num   = $info->goods_num;
                $v->price_info  = $price_info;
            }
        }

        return $goods;
    }

    public static function change($data)
    {
        $re = self::whereGoodsId($data->goods_id)
            ->where('modular_type', $data->modular_type)
            ->update([
                'priceclassify_id'   => htmlspecialchars($data->priceclassify_id),
                'priceclassify_name' => ModularData::modularTypeToGetPriceclassifyTableClass($data->modular_type)->where('priceclassify_id', $data->priceclassify_id)->value('priceclassify_name'),
                'unit_price'         => htmlspecialchars($data->price),
                'total_price'        => htmlspecialchars($data->price),
            ]);
        if (!$re)
            throw new Exception('操作失败');

        return true;
    }
}