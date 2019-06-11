<?php


namespace App\Service;


use App\Models\Selfmedia\GoodsSelfmedia;
use App\Models\Selfmedia\GoodsSelfmediaPrice;
use App\Models\Softarticle\GoodsSoftarticle;
use App\Models\Softarticle\GoodsSoftarticlePrice;
use App\Models\Video\GoodsVideo;
use App\Models\Video\GoodsVideoPrice;
use App\Models\Weibo\GoodsWeibo;
use App\Models\Weibo\GoodsWeiboPrice;
use App\Models\Weixin\GoodsWeixin;
use App\Models\Weixin\GoodsWeixinPrice;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;

/**
 * App\Service\ModularData
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service\ModularData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service\ModularData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service\ModularData query()
 * @mixin \Eloquent
 */
class ModularData extends Model
{
    // 根据模块类型 获取商品表[goods_xxx] 对象
    public static function modularTypeToGetGoodsTableClass($modularType)
    {
        switch ($modularType) {
            case 'WEIXIN':
                $object = new GoodsWeixin;
                break;
            case 'WEIBO':
                $object = new GoodsWeibo;
                break;
            case 'VIDEO':
                $object = new GoodsVideo;
                break;
            case 'SELFMEDIA':
                $object = new GoodsSelfmedia;
                break;
            case 'SOFTARTICLE':
                $object = new GoodsSoftarticle;
                break;
        }

        return $object;
    }

    // 根据模块类型获取 价格种类表[xxx_priceclassify] 对象
    public static function modularTypeToGetPriceclassifyTableClass($modularType)
    {
        switch ($modularType) {
            case 'WEIXIN':
                $object = new \App\Models\Weixin\Priceclassify;
                break;
            case 'WEIBO':
                $object = new \App\Models\Weibo\Priceclassify;
                break;
            case 'VIDEO':
                $object = new \App\Models\Video\Priceclassify;
                break;
            case 'SELFMEDIA':
                $object = new \App\Models\Selfmedia\Priceclassify;
                break;
            case 'SOFTARTICLE':
                $object = new \App\Models\Softarticle\Priceclassify;
                break;
        }

        return $object;
    }

    // 根据模块类型获取 商品价格表[goods_xxx_price] 对象
    public static function modularTypeToGetGoodsPriceTableClass($modularType)
    {
        switch ($modularType) {
            case 'WEIXIN':
                $object = new GoodsWeixinPrice;
                break;
            case 'WEIBO':
                $object = new GoodsWeiboPrice;
                break;
            case 'VIDEO':
                $object = new GoodsVideoPrice;
                break;
            case 'SELFMEDIA':
                $object = new GoodsSelfmediaPrice;
                break;
            case 'SOFTARTICLE':
                $object = new GoodsSoftarticlePrice;
                break;
        }

        return $object;
    }

    // 查询对应模块商品信息
    public static function goodsInfo($modularType, $parm, $attr)
    {
        $tableClass = self::modularTypeToGetGoodsTableClass($modularType);

        switch ($attr) {
            // 根据 uid 查询
            case 'uid':
                $goods = $tableClass->whereUid($parm)->orderBy('created_at', 'DESC')->get();
                break;

            // 根据 goods_id数组 查询
            case 'goods_id_arr':
                $goods = $tableClass->whereIn('goods_id', $parm)->get();
                break;
        }

        // 插入价格信息
        $re = self::withPriceInfo($modularType, $goods);

        return $re;
    }

    // 跳转到插入价格信息
    public static function withPriceInfo($modularType, $goods)
    {
        $re = self::modularTypeToGetGoodsPriceTableClass($modularType)::withPriceInfo($goods);

        return $re;
    }

    // 检查模块类型
    public static function checkModularType($modularType)
    {
        if (!in_array($modularType, array_keys(type("MODULAR_TYPE"))))
            throw new Exception('模块类型错误');

        return true;
    }

    // 判断商品是否存在
    public static function checkGoodsHas($modular_type, $goods_id)
    {
        // 根据模块类型获取商品表对象
        $table = ModularData::modularTypeToGetGoodsTableClass($modular_type);
        $re    = $table->find($goods_id);
        if (!$re)
            throw new Exception('商品不存在');

        return true;
    }
}