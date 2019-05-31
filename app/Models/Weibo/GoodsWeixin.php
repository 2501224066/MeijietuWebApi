<?php


namespace App\Models\Weibo;


use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Weixin\Theme;
use App\Models\Weixin\Filed;
use App\Models\Currency\Region;
use App\Models\Weixin\Priceclassify;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * App\Models\Weibo\GoodsWeixin
 *
 * @property int $goods_weixin_id 商品id UUID
 * @property int $theme_id 主题id
 * @property string $theme_name 主题名称
 * @property string $goods_title 商品名称（微信名称）
 * @property string $goods_title_about 商品名称简介
 * @property string $weixin_ID 微信号
 * @property int $filed_id 领域id
 * @property string $filed_name 领域名称
 * @property int $fans_num 粉丝数
 * @property int $region_id 面向地区id
 * @property string $region_name 面向地区
 * @property int $reserve_status 是否需要预约 0=否 1=是
 * @property string|null $remarks 备注
 * @property int $qq_ID 联系qq
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereFansNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereFiledName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereGoodsTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereGoodsTitleAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereGoodsWeixinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereQqID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereReserveStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeixin whereWeixinID($value)
 * @mixin \Eloquent
 */
class GoodsWeixin extends Model
{
    use Uuids;

    protected $table = 'goods_weixin';

    protected $primaryKey = 'goods_weixin_id';

    public $guarded = [];

    public $incrementing = false;

    // 添加商品
    public static function add($data)
    {
        $date = date('Y-m-d H:i:s');
        DB::transaction(function () use ($data, $date){
            // 添加微信商品
            $goods_weixin_id = DB::table('goods_weixin')
                ->insertGetId([
                    'goods_num' => createGoodsNnm(),
                    'theme_id' => htmlspecialchars($data->theme_id),
                    'uid' => JWTAuth::user()->uid,
                    'theme_name' => Theme::whereThemeId($data->theme_id)->value('theme_name'),
                    'goods_title' => htmlspecialchars($data->goods_title),
                    'goods_title_about' => htmlspecialchars($data->goods_title_about),
                    'weixin_ID' => htmlspecialchars($data->weixin_ID),
                    'filed_id' => htmlspecialchars($data->filed_id),
                    'filed_name' => Filed::whereFiledId($data->filed_id)->value('filed_name'),
                    'fans_num' => htmlspecialchars($data->fans_num),
                    'region_id' => htmlspecialchars($data->region_id),
                    'region_name' => Region::whereRegionId($data->region_id)->value('region_name'),
                    'reserve_status' => htmlspecialchars($data->reserve_status),
                    'remarks' =>  htmlspecialchars($data->remarks),
                    'qq_ID' => htmlspecialchars($data->qq_ID),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            if ( ! $goods_weixin_id)
                throw new Exception('保存失败');

            // 添加微信商品价格
            $price_data = json_decode($data->price_data);
            foreach ($price_data as $k => $v) {
                $reTwo = DB::table('goods_weixin_price')
                    ->insert([
                        'goods_weixin_id' => $goods_weixin_id,
                        'priceclassify_id' => $k,
                        'priceclassify_name' => Priceclassify::wherePriceclassifyId($k)->value('priceclassify_name'),
                        'price' => $v,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                if ( ! $reTwo)
                    throw new Exception('保存失败');
            }
        });

        return true;
    }
}