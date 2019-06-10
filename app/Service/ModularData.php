<?php


namespace App\Service;


use App\Models\Selfmedia\GoodsSelfmedia;
use App\Models\Softarticle\GoodsSoftarticle;
use App\Models\Video\GoodsVideo;
use App\Models\Video\GoodsVideoPrice;
use App\Models\Weibo\GoodsWeibo;
use App\Models\Weibo\GoodsWeiboPrice;
use App\Models\Weixin\GoodsWeixin;
use App\Models\Weixin\GoodsWeixinPrice;
use Illuminate\Database\Eloquent\Model;

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
    // 根据模块类型获取商品表对象
    public static function modularTypeToGetGoodsTableClass($modularType)
    {
        switch ($modularType) {
            case 'WEIXIN':
                $table = new GoodsWeixin;
                break;
            case 'WEIBO':
                $table = new GoodsWeibo;
                break;
            case 'VIDEO':
                $table = new GoodsVideo;
                break;
            case 'SELFMEDIA':
                $table = new GoodsSelfmedia;
                break;
            case 'SOFTARTICLE':
                $table = new GoodsSoftarticle;
                break;
        }

        return $table;
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

    // 插入价格信息
    public static function withPriceInfo($modularType, $goods)
    {
        switch ($modularType) {
            case 'WEIXIN':
                $re = GoodsWeixinPrice::withPriceInfo($goods);
                break;
            case 'WEIBO':
                $re = GoodsWeiboPrice::withPriceInfo($goods);
                break;
            case 'VIDEO':
                $re = GoodsVideoPrice::withPriceInfo($goods);
                break;
            case 'SELFMEDIA':
                $re = $goods;
                break;
            case 'SOFTARTICLE':
                $re = $goods;
                break;
        }

        return $re;
    }
}