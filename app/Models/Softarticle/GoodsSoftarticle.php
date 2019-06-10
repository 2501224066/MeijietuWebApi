<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Currency\Region;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\Softarticle\GoodsSoftarticle
 *
 * @property int $goods_id 商品id
 * @property string $modular_name 模块名称
 * @property int|null $uid 用户id
 * @property string $goods_num 商品编号
 * @property string|null $goods_title 商品名称
 * @property string|null $goods_title_about 商品名称简介
 * @property string|null $web_link 链接网址
 * @property int|null $news_source 新闻源 0=否 1=是
 * @property int|null $weekend_send 周末是否发稿 0=否 1=是
 * @property string|null $setmeal_title 套餐分类
 * @property string|null $setmeal_about 套餐简介
 * @property string|null $setmeal_content 套餐内容
 * @property int $theme_id 主题id
 * @property string $theme_name 主题名称
 * @property int|null $filed_id 领域id
 * @property string|null $filed_name 领域名称
 * @property int|null $platform_id 平台id
 * @property string|null $platform_name 平台名称
 * @property int|null $industry_id 行业id
 * @property string|null $industry_name 行业名称
 * @property int|null $sendspeed_id 发稿速度id
 * @property string|null $sendspeed_name 发稿速度
 * @property int|null $entryclassify_id 入口种类id
 * @property string|null $entryclassify_name 入口种类
 * @property int|null $region_id 面向地区id
 * @property string|null $region_name 面向地区
 * @property string|null $qq_ID 联系qq
 * @property float|null $price 价格
 * @property int|null $verify_status 审核状态 0=审核中 1=审核不通过 2=审核通过
 * @property int|null $status 状态 0=下架 1=上架
 * @property string|null $remarks 备注
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereEntryclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereEntryclassifyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereFiledName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereGoodsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereGoodsTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereGoodsTitleAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereIndustryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereIndustryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereModularName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereNewsSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle wherePlatformName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereQqID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereSendspeedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereSendspeedName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereSetmealAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereSetmealContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereSetmealTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereVerifyStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereWebLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\GoodsSoftarticle whereWeekendSend($value)
 * @mixin \Eloquent
 */
class GoodsSoftarticle extends Model
{

    protected $table = 'goods_softarticle';

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
                'goods_num'          => createGoodsNnm(),
                'uid'                => JWTAuth::user()->uid,
                'goods_title'        => htmlspecialchars($data->goods_title),
                'goods_title_about'  => htmlspecialchars($data->goods_title_about),
                'web_link'           => htmlspecialchars($data->web_link),
                'weekend_send'       => htmlspecialchars($data->weekend_send),
                'news_source'        => htmlspecialchars($data->news_source),
                'theme_id'           => htmlspecialchars($data->theme_id),
                'theme_name'         => Theme::whereThemeId($data->theme_id)->value('theme_name'),
                'platform_id'        => htmlspecialchars($data->platform_id),
                'platform_name'      => Platform::wherePlatformId($data->platform_id)->value('platform_name'),
                'filed_id'           => htmlspecialchars($data->filed_id),
                'filed_name'         => Filed::whereFiledId($data->filed_id)->value('filed_name'),
                'region_id'          => htmlspecialchars($data->region_id),
                'region_name'        => Region::whereRegionId($data->region_id)->value('region_name'),
                'sendspeed_id'       => htmlspecialchars($data->sendspeed_id),
                'sendspeed_name'     => Sendspeed::whereSendspeedId($data->sendspeed_id)->value('sendspeed_name'),
                'industry_id'        => htmlspecialchars($data->industry_id),
                'industry_name'      => Industry::whereIndustryId($data->industry_id)->value('industry_name'),
                'entryclassify_id'   => htmlspecialchars($data->entryclassify_id),
                'entryclassify_name' => Entryclassify::whereEntryclassifyId($data->entryclassify_id)->value('entryclassify_name'),
                'qq_ID'              => htmlspecialchars($data->qq_ID),
                'remarks'            => htmlspecialchars($data->remarks),
                'created_at'         => $now,
                'updated_at'         => $now
            ]);
            if (!$goods_id)
                throw new Exception('保存失败');

            // 添加商品价格
            $price_data = json_decode($data->price_data);
            foreach ($price_data as $k => $v) {
                $reTwo = GoodsSoftarticlePrice::create([
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
    public static function select($data, $idArr)
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

        if ($data->sendspeed_id)
            $query->where('sendspeed_id', '=', $data->sendspeed_id);

        if ($data->industry_id)
            $query->where('industry_id', '=', $data->industry_id);

        if ($data->entryclassify_id)
            $query->where('entryclassify_id', '=', $data->entryclassify_id);

        if ($data->region_id)
            $query->where('region_id', '=', $data->region_id);

        if ($data->keyword)
            $query->where('good_title', 'like', '%' . $data->keyword . '%');

        $re = $query->paginate();

        return $re;
    }
}