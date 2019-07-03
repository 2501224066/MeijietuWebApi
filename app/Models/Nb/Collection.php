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
 * @property int $collection_id
 * @property int $uid
 * @property int $goods_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Nb\Goods $goods
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\Collection whereCollectionId($value)
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

    public function goods(): HasOne
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
            try {
                foreach ($goodsIdArr as $goodsId) {
                    self::firstOrCreate([
                        'uid'      => $uid,
                        'goods_id' => $goodsId,
                    ]);

                }
            } catch (\Exception $e) {
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

}