<?php


namespace App\Models\Selfmedia;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Currency\Region;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\Selfmedia\GoodsSelfmedia
 *
 * @property int $goods_id 商品id
 * @property string $modular_name 模块名称
 * @property int $uid 用户id
 * @property string $goods_num 商品编号
 * @property string $goods_title 商品名称
 * @property string $goods_title_about 商品名称简介
 * @property int $fans_num 粉丝数量
 * @property int $theme_id 主题id
 * @property string $theme_name 主题名称
 * @property int $filed_id 领域id
 * @property string $filed_name 领域名称
 * @property int $platform_id 平台id
 * @property string $platform_name 平台名称
 * @property int $region_id 面向地区id
 * @property string $region_name 面向地区
 * @property int $reserve_status 是否需要预约 0=否 1=是
 * @property string $qq_ID 联系qq
 * @property float $price 价格
 * @property int $verify_status 审核状态 0=审核中 1=审核不通过 2=审核通过
 * @property int $status 状态 0=下架 1=上架
 * @property string|null $remarks 备注
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereFansNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereFiledName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereGoodsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereGoodsTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereGoodsTitleAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereModularName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia wherePlatformName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereQqID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereReserveStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\GoodsSelfmedia whereVerifyStatus($value)
 * @mixin \Eloquent
 */
class GoodsSelfmedia extends Model
{

    protected $table = 'goods_selfmedia';

    protected $primaryKey = 'goods_id';

    public $guarded = [];

    const STATUS_ON = 1; // 上架

    const STATUS_OFF = 0; // 下架

    const VERIFY_STATUS_WAIT = 0; // 审核中

    const VERIFY_STATUS_SUCC = 1; // 审核通过

    const VERIFY_STATUS_FAIL = 2; // 审核不通过

    // 添加商品
    public static function add($data)
    {
        $now      = date('Y-m-d H:i:s');
        $goods_id = null;
        DB::transaction(function () use ($data, $now, &$goods_id) {

            // 添加商品
            $goods_id = self::insertGetId([
                'goods_num'         => createGoodsNnm(),
                'uid'               => JWTAuth::user()->uid,
                'goods_title'       => htmlspecialchars($data->goods_title),
                'goods_title_about' => htmlspecialchars($data->goods_title_about),
                'fans_num'          => htmlspecialchars($data->fans_num),
                'theme_id'          => htmlspecialchars($data->theme_id),
                'theme_name'        => Theme::whereThemeId($data->theme_id)->value('theme_name'),
                'platform_id'       => htmlspecialchars($data->platform_id),
                'platform_name'     => Platform::wherePlatformId($data->platform_id)->value('platform_name'),
                'filed_id'          => htmlspecialchars($data->filed_id),
                'filed_name'        => Filed::whereFiledId($data->filed_id)->value('filed_name'),
                'region_id'         => htmlspecialchars($data->region_id),
                'region_name'       => Region::whereRegionId($data->region_id)->value('region_name'),
                'qq_ID'             => htmlspecialchars($data->qq_ID),
                'remarks'           => htmlspecialchars($data->remarks),
                'created_at'         => $now,
                'updated_at'         => $now
            ]);
            if (!$goods_id)
                throw new Exception('保存失败');

            // 添加商品价格
            $price_data = json_decode($data->price_data);
            foreach ($price_data as $k => $v) {
                $reTwo = GoodsSelfmediaPrice::create([
                    'goods_id'           => $goods_id,
                    'priceclassify_id'   => $k,
                    'priceclassify_name' => Priceclassify::wherePriceclassifyId($k)->value('priceclassify_name'),
                    'price'              => $v,
                ]);
                if (!$reTwo)
                    throw new Exception('保存失败');
            }
        });

        return $goods_id;
    }

    // 拼装条件并查询
    public static function select($data,  $idArr)
    {
        $query = self::whereIn('goods_id', $idArr)
            ->where('theme_id', $data->theme_id)
            ->where('status', self::STATUS_ON);

        if ($data->pricelevel_min)
            $query->where('price', '>', $data->pricelevel_min);

        if ($data->pricelevel_max)
            $query->where('price', '<=', $data->pricelevel_max);

        if ($data->filed_id)
            $query->where('filed_id', $data->filed_id);

        if ($data->platform_id)
            $query->where('platform_id', '=', $data->platform_id);

        if ($data->fansnumlevel_min)
            $query->where('fans_num', '>', $data->fansnumlevel_min);

        if ($data->fansnumlevel_max)
            $query->where('fans_num', '<=', $data->fansnumlevel_max);

        if ($data->region_id)
            $query->where('region_id', '=', $data->region_id);

        if ($data->keyword)
            $query->where('good_title', 'like', '%' . $data->keyword . '%');

        $re = $query->paginate();

        return $re;
    }
}