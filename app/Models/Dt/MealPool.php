<?php


namespace App\Models\Dt;


use App\Models\Nb\Goods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

/**
 * App\Models\Dt\MealPool
 *
 * @property int $id
 * @property string|null $pool_name 池名称
 * @property int $pid 父id
 * @property int|null $goods_id 商品id
 * @property string|null $title 商品名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\MealPool newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\MealPool newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\MealPool query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\MealPool whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\MealPool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\MealPool wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\MealPool wherePoolName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\MealPool whereTitle($value)
 * @mixin \Eloquent
 */
class MealPool extends Model
{
    protected $table = 'dt_mealpool';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    // 创建套餐池操作
    public static function createMealPoolOP($goodsIdArr, $poolName)
    {
        DB::transaction(function () use ($goodsIdArr, $poolName) {
            try {
                // 创建池
                $id = self::insertGetId(["pool_name" => $poolName]);

                // 向池中加入商品
                foreach ($goodsIdArr as $goodsId) {
                    $goods = Goods::whereGoodsId($goodsId)->first();
                    if (($goods->status == Goods::STATUS['上架'])
                        && ($goods->verify_status == Goods::VERIFY_STATUS['已通过'])
                        && ($goods->delete_status == Goods::DELETE_STATUS['未删除'])
                        && (self::whereGoodsId($goods->goods_id)->where('pid', $id)->count() == 0)) {
                        self::create([
                            'pid'      => $id,
                            'goods_id' => $goods->goods_id,
                            'title'    => $goods->title
                        ]);
                    }
                }
            } catch (Exception $e) {
                throw new Exception('操作失败');
            }
        });

        return true;
    }
}