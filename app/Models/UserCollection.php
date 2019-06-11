<?php


namespace App\Models;


use App\Service\ModularData;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * App\Models\UserCollection
 *
 * @property int $collection_id 收藏id
 * @property int $uid 用户id
 * @property string $modular_type 模块类型
 * @property int $goods_id 商品id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCollection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCollection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCollection query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCollection whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCollection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCollection whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCollection whereModularType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCollection whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCollection whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserCollection extends Model
{
    protected $table = 'user_collection';

    public $guarded = [];


    // 判断商品是否已经收藏
    public static function checkCollectionHas($data)
    {
        $count = self::whereModularType($data->modular_type)
            ->where('goods_id', $data->goods_id)
            ->count();
        if ($count)
            throw new Exception('此商品已收藏');

        return true;
    }

    // 添加收藏
    public static function add($data)
    {
        $re = self::create([
            'uid'              => JWTAuth::user()->uid,
            'goods_id' => htmlspecialchars($data->goods_id),
            'modular_type'     => htmlspecialchars($data->modular_type)
        ]);
        if (!$re)
            throw new Exception('收藏失败');

        return true;
    }

    // 删除收藏
    public static function del($idArr)
    {
        foreach ($idArr as $id) {
            $re = self::whereCollectionId($id)->delete();
            if (!$re)
                throw new Exception('操作失败');
        }

        return true;
    }

    // 对应模块收藏信息
    public static function modularTypeCollectionInfo($modularType)
    {
        $uid = JWTAuth::user()->uid;
        $goodsIdArr = UserCollection::whereUid($uid)
            ->where('modular_type',$modularType)
            ->pluck('goods_id');
        // 查询对应模块商品信息
        $re = ModularData::goodsInfo($modularType, $goodsIdArr, 'goods_id_arr');

        return $re;
    }
}