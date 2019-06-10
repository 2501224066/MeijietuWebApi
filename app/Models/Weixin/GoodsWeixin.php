<?php


namespace App\Models\Weixin;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Currency\Region;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\Weixin\GoodsWeixin
 *
 * @property int $goods_id 商品id
 * @property string $modular_name 模块名称
 * @property int $uid 用户id
 * @property string $goods_num 商品编号
 * @property string $goods_title 商品名称（微信名称）
 * @property string $goods_title_about 商品名称简介
 * @property string $weixin_ID 微信号
 * @property string|null $avatar_url 头像
 * @property string|null $qrcode_url 二维码
 * @property int $fans_num 粉丝数
 * @property int|null $avg_read_num 平均阅读数
 * @property int|null $avg_like_num 平均点赞数
 * @property int|null $avg_comment_num 平均评论数
 * @property int $theme_id 主题id
 * @property string $theme_name 主题名称
 * @property int $filed_id 领域id
 * @property string $filed_name 领域名称
 * @property int $region_id 面向地区id
 * @property string $region_name 面向地区
 * @property int $reserve_status 是否需要预约 0=否 1=是
 * @property string $qq_ID 联系qq
 * @property int $verify_status 审核状态 0=审核中 1=审核不通过 2=审核通过
 * @property int $status 状态 0=下架 1=上架
 * @property string|null $remarks 备注
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereAvgCommentNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereAvgLikeNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereAvgReadNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereFansNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereFiledName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereGoodsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereGoodsTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereGoodsTitleAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereModularName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereQqID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereQrcodeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereReserveStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereVerifyStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\GoodsWeixin whereWeixinID($value)
 * @mixin \Eloquent
 */
class GoodsWeixin extends Model
{

    protected $table = 'goods_weixin';

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
        $goods_id = null;
        DB::transaction(function () use ($data, &$goods_id) {

            // 添加微信商品
            $goods_id = self::insertGetId([
                'goods_num'         => createGoodsNnm(),
                'theme_id'          => htmlspecialchars($data->theme_id),
                'uid'               => JWTAuth::user()->uid,
                'theme_name'        => Theme::whereThemeId($data->theme_id)->value('theme_name'),
                'goods_title'       => htmlspecialchars($data->goods_title),
                'goods_title_about' => htmlspecialchars($data->goods_title_about),
                'weixin_ID'         => htmlspecialchars($data->weixin_ID),
                'filed_id'          => htmlspecialchars($data->filed_id),
                'filed_name'        => Filed::whereFiledId($data->filed_id)->value('filed_name'),
                'fans_num'          => htmlspecialchars($data->fans_num),
                'region_id'         => htmlspecialchars($data->region_id),
                'region_name'       => Region::whereRegionId($data->region_id)->value('region_name'),
                'reserve_status'    => htmlspecialchars($data->reserve_status),
                'qq_ID'             => htmlspecialchars($data->qq_ID),
                'remarks'           => htmlspecialchars($data->remarks),
            ]);
            if (!$goods_id)
                throw new Exception('保存失败');

            // 添加微信商品价格
            $price_data = json_decode($data->price_data);
            foreach ($price_data as $k => $v) {
                $reTwo = GoodsWeixinPrice::insert([
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

        if ($data->filed_id)
            $query->where('filed_id', $data->filed_id);

        if ($data->fansnumlevel_min)
            $query->where('fans_num', '>', $data->fansnumlevel_min);

        if ($data->fansnumlevel_max)
            $query->where('fans_num', '<=', $data->fansnumlevel_max);

        if ($data->readlevel_min)
            $query->where('read_num', '>', $data->readlevel_min);

        if ($data->readleve_max)
            $query->where('read_num', '<=', $data->readleve_max);

        if ($data->region_id)
            $query->where('region_id', '=', $data->region_id);

        if ($data->keyword)
            $query->where('good_title', 'like', '%' . $data->keyword . '%');

        $re = $query->paginate();

        return $re;
    }
}