<?php


namespace App\Models\Nb;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\Nb\UserCollection
 *
 * @property int $uid 用户id
 * @property int $goods_id 商品id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserCollection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserCollection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserCollection query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserCollection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserCollection whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserCollection whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nb\UserCollection whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserCollection extends Model
{
    protected $table = 'nb_user_collection';

    public $guarded = [];

    // 转换结构
    public static function changeStruct($goodsIdArr)
    {
        $arr = [];
        $uid = JWTAuth::user()->uid;
        foreach ($goodsIdArr as $goodsId) {
            if ( ! self::checkCollectionStatus($uid, $goodsId)) {
                $arr[] = [
                    'uid'      => $uid,
                    'goods_id' => $goodsId
                ];
            }
        }

        return $arr;
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
    public static function add($arr)
    {
        $re = self::create($arr);
        if (!$re)
            throw new Exception('保存失败');

        return true;
    }
}