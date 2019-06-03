<?php


namespace App\Models\Video;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Currency\Region;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;



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