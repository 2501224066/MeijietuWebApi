<?php


namespace App\Models\Nb;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\Nb\Collection
 *
 * @property int $uid 用户id
 * @property int $goods_id 商品id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Collection whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Collection whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Collection whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Collection extends Model
{
    protected $table = 'nb_collection';

    public $guarded = [];

    public function goods() : HasOne
    {
        return $this->hasOne(Goods::class, 'goods_id', 'goods_id');
    }

    // 检查收藏状态
    public static function checkCollectionStatus($uid, $goodsId)
    {
        $count = self::whereUid($uid)
            ->where('goods_id', $goodsId)
            ->count();

        return $count ? true : false;
    }

    // 添加收藏
    public static function add($goodsIdArr)
    {
        $uid = JWTAuth::user()->uid;
        DB::transaction(function () use ($goodsIdArr, $uid) {
            foreach ($goodsIdArr as $goodsId => $goodsPriceId) {
                $re = self::firstOrCreate([
                    'uid'      => $uid,
                    'goods_id' => $goodsId,
                     'goods_price_id' => $goodsPriceId
                ]);
                if (!$re)
                    throw new Exception('保存失败');
            }
        });

        return true;
    }

    // 获取个人收藏
    public static function getCollection()
    {
        $uid = JWTAuth::user()->uid;
        return self::with('goods.goods_price')
            ->where('uid', $uid)
            ->get();
    }

    // 删除收藏
    public static function del($goodsIdArr)
    {
        $uid = JWTAuth::user()->uid;
        foreach ($goodsIdArr as $goodsId => $goodsPriceId) {
            self::whereUid($uid)
            ->where( 'goods_id', $goodsId)
                ->where( 'goods_price_id', $goodsPriceId)
            ->delete();
        }

        return true;
    }
}