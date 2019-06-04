<?php


namespace App\Models\Video;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Currency\Region;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;



/**
 * App\Models\Video\GoodsVideo
 *
 * @property int $goods_video_id 商品id
 * @property int $uid 用户id
 * @property string $goods_num 商品编号
 * @property string $goods_title 商品名称
 * @property string $goods_title_about 商品名称简介
 * @property string $room_num 房间号
 * @property int $fans_num 粉丝数
 * @property int $theme_id 主题id
 * @property string $theme_name 主题名称
 * @property int $platform_id 平台id
 * @property string $platform_name 平台名称
 * @property string $logo_path 平台图标
 * @property int $filed_id 领域id
 * @property string $filed_name 领域名称
 * @property int $region_id 面向地区id
 * @property string $region_name 面向地区
 * @property string $qq_ID 联系qq
 * @property int $verify_status 审核状态 0=审核中 1=审核不通过 2=审核通过
 * @property int $status 状态 0=下架 1=上架
 * @property string|null $remarks 备注
 * @property string|null $basic_data 基础数据
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereBasicData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereFansNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereFiledName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereGoodsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereGoodsTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereGoodsTitleAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereGoodsVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo wherePlatformName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereQqID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereRoomNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\GoodsVideo whereVerifyStatus($value)
 * @mixin \Eloquent
 */
class GoodsVideo extends Model
{

    protected $table = 'goods_video';

    protected $primaryKey = 'goods_video_id';

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
        $goods_video_id = null;
        DB::transaction(function () use ($data, $date, &$goods_video_id){
            $platfrom = Platform::wherePlatformId($data->platform_id)->first();

            // 添加商品
            $goods_video_id = DB::table('goods_video')
                ->insertGetId([
                    'goods_num' => createGoodsNnm(),
                    'theme_id' => htmlspecialchars($data->theme_id),
                    'uid' => JWTAuth::user()->uid,
                    'theme_name' => Theme::whereThemeId($data->theme_id)->value('theme_name'),
                    'goods_title' => htmlspecialchars($data->goods_title),
                    'goods_title_about' => htmlspecialchars($data->goods_title_about),
                    'room_num' => htmlspecialchars($data->room_num),
                    'fans_num' => htmlspecialchars($data->fans_num),
                    'platform_id' => htmlspecialchars($data->platform_id),
                    'platform_name' =>  $platfrom->platform_name,
                    'logo_path' => $platfrom->logo_path,
                    'filed_id' => htmlspecialchars($data->filed_id),
                    'filed_name' => Filed::whereFiledId($data->filed_id)->value('filed_name'),
                    'region_id' => htmlspecialchars($data->region_id),
                    'region_name' => Region::whereRegionId($data->region_id)->value('region_name'),
                    'qq_ID' => htmlspecialchars($data->qq_ID),
                    'verify_status' => self::VERIFY_STATUS_WAIT,
                    'status' => self::STATUS_OFF,
                    'remarks' =>  htmlspecialchars($data->remarks),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            if ( ! $goods_video_id)
                throw new Exception('保存失败');

            // 添加商品价格
            $price_data = json_decode($data->price_data);
            foreach ($price_data as $k => $v) {
                $reTwo = DB::table('goods_video_price')
                    ->insert([
                        'goods_video_id' => $goods_video_id,
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

        return $goods_video_id;
    }
}