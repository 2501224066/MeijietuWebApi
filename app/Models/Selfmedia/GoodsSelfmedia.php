<?php


namespace App\Models\Selfmedia;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Currency\Region;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;



class GoodsSelfmedia extends Model
{

    protected $table = 'goods_selfmedia';

    protected $primaryKey = 'goods_selfmedia_id';

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
        $goods_selfmedia_id = DB::table('goods_selfmedia')
            ->insertGetId([
                'goods_num' => createGoodsNnm(),
                'theme_id' => htmlspecialchars($data->theme_id),
                'uid' => JWTAuth::user()->uid,
                'theme_name' => Theme::whereThemeId($data->theme_id)->value('theme_name'),
                'goods_title' => htmlspecialchars($data->goods_title),
                'goods_title_about' => htmlspecialchars($data->goods_title_about),
                'platform_id' => htmlspecialchars($data->platform_id),
                'platform_name' =>  Platform::wherePlatformId($data->platform_id)->value('platform_name'),
                'filed_id' => htmlspecialchars($data->filed_id),
                'filed_name' => Filed::whereFiledId($data->filed_id)->value('filed_name'),
                'region_id' => htmlspecialchars($data->region_id),
                'region_name' => Region::whereRegionId($data->region_id)->value('region_name'),
                'qq_ID' => htmlspecialchars($data->qq_ID),
                'price' => htmlspecialchars($data->price),
                'verify_status' => self::VERIFY_STATUS_WAIT,
                'status' => self::STATUS_OFF,
                'remarks' =>  htmlspecialchars($data->remarks),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        if ( ! $goods_selfmedia_id)
            throw new Exception('保存失败');

        return $goods_selfmedia_id;
    }
}