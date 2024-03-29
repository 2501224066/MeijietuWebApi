<?php


namespace App\Models\Data;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\Data\Shopcart
 *
 * @property int $shopcart_id
 * @property int $uid 用户id
 * @property int $goods_id 商品id
 * @property int $goods_price_id 商品价格id
 * @property int $goods_count 商品数量
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Data\Goods $goods
 * @property-read \App\Models\Data\GoodsPrice $goods_price
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Shopcart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Shopcart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Shopcart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Shopcart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Shopcart whereGoodsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Shopcart whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Shopcart whereGoodsPriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Shopcart whereShopcartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Shopcart whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Shopcart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Shopcart extends Model
{
    protected $table = 'data_shopcart';

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
                        ->lockForUpdate()
                        ->first()
                        ->toArray();

                    // 检查商品信息
                    Goods::checkGoodsData($goodsData);

                    // 加入
                    self::updateOrCreate([
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