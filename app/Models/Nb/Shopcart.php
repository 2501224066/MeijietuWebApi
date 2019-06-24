<?php


namespace App\Models\Nb;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

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
                    self::firstOrCreate([
                        'uid'            => $uid,
                        'goods_id'       => $goodsId,
                        'goods_price_id' => $goodsPriceId
                    ]);
                }
            } catch (\Exception $e) {
                throw new Exception('保存失败');
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

    // 删除购物车商品
    public static function del($goodsIdArr)
    {
        $uid = JWTAuth::user()->uid;
        foreach ($goodsIdArr as $goodsId => $goodsPriceId) {
            self::whereUid($uid)
                ->where('goods_id', $goodsId)
                ->where('goods_price_id', $goodsPriceId)
                ->delete();
        }

        return true;
    }
}