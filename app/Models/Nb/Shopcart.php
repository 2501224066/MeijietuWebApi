<?php


namespace App\Models\Nb;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\Nb\Shopcart
 *
 * @property int $shopcart_id
 * @property int $uid 用户id
 * @property int $goods_id 商品id
 * @property int $goods_price_id 商品价格id
 * @property int $goods_count 商品数量
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Nb\Goods $goods
 * @property-read \App\Models\Nb\GoodsPrice $goods_price
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Shopcart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Shopcart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Shopcart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Shopcart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Shopcart whereGoodsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Shopcart whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Shopcart whereGoodsPriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Shopcart whereShopcartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Shopcart whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Shopcart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Shopcart extends Model
{
    protected $table = 'nb_shopcart';

    protected $guarded = [];

    public function goods(): HasOne
    {
        return $this->hasOne(Goods::class, 'goods_id', 'goods_id');
    }

    public function goods_price(): HasOne
    {
        return $this->hasOne(GoodsPrice::class, 'goods_price_id', 'goods_price_id');
    }

    //加入购物车
    public static function join($goodsIdArr)
    {
        $uid = JWTAuth::user()->uid;
        DB::transaction(function () use ($goodsIdArr, $uid) {
            try {
                foreach ($goodsIdArr as $goodsId => $goodsPriceId) {
                    // 获取商品信息
                    $goodsData = Goods::with(['one_goods_price' => function ($query) use ($goodsId, $goodsPriceId) {
                        $query->where('goods_price_id', $goodsPriceId);
                    }])
                        ->where('goods_id', $goodsId)
                        ->first()
                        ->toArray();

                    // 检查商品信息
                    Goods::checkGoodsData($goodsData);

                    self::firstOrCreate([
                        'uid'            => $uid,
                        'goods_id'       => $goodsId,
                        'goods_price_id' => $goodsPriceId
                    ]);
                }
            } catch (\Exception $e) {
                throw new Exception('操作失败');
            }
        });

        return true;
    }

    // 获取购物车商品
    public static function getShopcart()
    {
        $uid = JWTAuth::user()->uid;
        return self::with(['goods', 'goods_price'])
            ->where('uid', $uid)
            ->get();
    }

}