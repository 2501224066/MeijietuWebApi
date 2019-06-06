<?php


namespace App\Models\Weibo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Currency\Region;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * App\Models\Weibo\GoodsWeibo
 *
 * @property int $goods_weibo_id 商品id
 * @property int $uid 用户id
 * @property string $goods_num 商品编号
 * @property string $goods_title 商品名称（微博名称）
 * @property string $goods_title_about 商品名称简介
 * @property string $weibo_link 微博链接
 * @property int $theme_id 主题id
 * @property string $theme_name 主题名称
 * @property int $filed_id 主题id
 * @property string $filed_name 领域名称
 * @property int $region_id 面向地区id
 * @property string $region_name 面向地区
 * @property int $reserve_status 是否需要预约 0=否 1=是
 * @property string $qq_ID 联系qq
 * @property int $verify_status 审核状态 0=审核中 1=审核不通过 2=审核通过
 * @property int $status 状态 0=下架 1=上架
 * @property string|null $remarks 备注
 * @property string|null $basic_data 基础数据
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereBasicData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereFiledName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereGoodsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereGoodsTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereGoodsTitleAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereGoodsWeiboId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereQqID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereReserveStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereVerifyStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereWeiboLink($value)
 * @mixin \Eloquent
 * @property int|null $authtype_id 认证类型id
 * @property string $authtype_name 认证类型
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereAuthtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\GoodsWeibo whereAuthtypeName($value)
 */
class GoodsWeibo extends Model
{
    protected $table = 'goods_weibo';

    protected $primaryKey = 'goods_weibo_id';

    public $guarded = [];

    const STATUS_ON = 1; // 上架

    const STATUS_OFF = 0; // 下架

    const VERIFY_STATUS_WAIT = 0; // 审核中

    const VERIFY_STATUS_SUCC = 1; // 审核通过

    const VERIFY_STATUS_FAIL = 2; // 审核不通过

    // 添加商品
    public static function add($data)
    {
        $date = date('Y-m-d H:i:s');
        $goods_weibo_id = null;
        DB::transaction(function () use ($data, $date, &$goods_weibo_id){
            // 添加商品
            $goods_weibo_id = DB::table('goods_weibo')
                ->insertGetId([
                    'goods_num' => createGoodsNnm(),
                    'theme_id' => htmlspecialchars($data->theme_id),
                    'uid' => JWTAuth::user()->uid,
                    'theme_name' => Theme::whereThemeId($data->theme_id)->value('theme_name'),
                    'goods_title' => htmlspecialchars($data->goods_title),
                    'goods_title_about' => htmlspecialchars($data->goods_title_about),
                    'weibo_link' => htmlspecialchars($data->weibo_link),
                    'filed_id' => htmlspecialchars($data->filed_id),
                    'filed_name' => Filed::whereFiledId($data->filed_id)->value('filed_name'),
                    'authtype_id' => htmlspecialchars($data->authtype_id),
                    'authtype_name' => Authtype::whereAuthtypeId($data->authtype_id)->value('authtype_name'),
                    'region_id' => htmlspecialchars($data->region_id),
                    'region_name' => Region::whereRegionId($data->region_id)->value('region_name'),
                    'reserve_status' => htmlspecialchars($data->reserve_status),
                    'qq_ID' => htmlspecialchars($data->qq_ID),
                    'verify_status' => self::VERIFY_STATUS_WAIT,
                    'status' => self::STATUS_OFF,
                    'remarks' =>  htmlspecialchars($data->remarks),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            if ( ! $goods_weibo_id)
                throw new Exception('保存失败');

            // 添加商品价格
            $price_data = json_decode($data->price_data);
            foreach ($price_data as $k => $v) {
                $reTwo = DB::table('goods_weibo_price')
                    ->insert([
                        'goods_weibo_id' => $goods_weibo_id,
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

        return $goods_weibo_id;
    }

    // 拼装条件并查询
    public static function select($data, $idArr)
    {
        $query = self::whereIn('goods_weibo_id', $idArr)
            ->where('theme_id', $data->theme_id);

        if ($data->filed_id)
            $query->where('filed_id', $data->filed_id);

        if ($data->fansnumlevel_min)
            $query->where('fans_num', '>', $data->fansnumlevel_min);

        if ($data->fansnumlevel_max)
            $query->where('fans_num', '<=', $data->fansnumlevel_max);

        if ($data->authtype_id)
            $query->where('authtype_id', '=', $data->authtype_id);

        if ($data->region_id)
            $query->where('region_id', '=', $data->region_id);

        if ($data->keyword)
            $query->where('good_title', 'like', '%'. $data->keyword. '%');

        $re = $query->paginate();

        return $re;
    }

    // 用户商品
    public static function userGoods($uid)
    {
        $goods = self::whereUid($uid)->orderBy('created_at', 'DESC')->get();

        // 插入价格信息
        $re = GoodsWeiboPrice::withPriceInfo($goods);

        return $re;
    }
}