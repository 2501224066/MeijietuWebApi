<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Currency\Region;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;



class GoodsSoftarticle extends Model
{

    protected $table = 'goods_softarticle';

    protected $primaryKey = 'goods_softarticle_id';

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

        // 添加商品
        $goods_softarticle_id = DB::table('goods_softarticle')
            ->insertGetId([
                'goods_num' => createGoodsNnm(),
                'uid' => JWTAuth::user()->uid,
                'goods_title' => htmlspecialchars($data->goods_title),
                'goods_title_about' => htmlspecialchars($data->goods_title_about),
                'web_link' => htmlspecialchars($data->web_link),
                'weekend_send' => htmlspecialchars($data->weekend_send),
                'news_source' => htmlspecialchars($data->news_source),
                'theme_id' => htmlspecialchars($data->theme_id),
                'theme_name' => Theme::whereThemeId($data->theme_id)->value('theme_name'),
                'platform_id' => htmlspecialchars($data->platform_id),
                'platform_name' => Platform::wherePlatformId($data->platform_id)->value('platform_name'),
                'filed_id' => htmlspecialchars($data->filed_id),
                'filed_name' => Filed::whereFiledId($data->filed_id)->value('filed_name'),
                'region_id' => htmlspecialchars($data->region_id),
                'region_name' => Region::whereRegionId($data->region_id)->value('region_name'),
                'sendspeed_id' => htmlspecialchars($data->sendspeed_id),
                'sendspeed_name' => Sendspeed::whereSendspeedId($data->sendspeed_id)->value('sendspeed_name'),
                'industry_id' => htmlspecialchars($data->industry_id),
                'industry_name' => Industry::whereIndustryId($data->industry_id)->value('industry_name'),
                'qq_ID' => htmlspecialchars($data->qq_ID),
                'price' => htmlspecialchars($data->price),
                'verify_status' => self::VERIFY_STATUS_WAIT,
                'status' => self::STATUS_OFF,
                'remarks' =>  htmlspecialchars($data->remarks),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        if ( ! $goods_softarticle_id)
            throw new Exception('保存失败');

        return $goods_softarticle_id;
    }
}