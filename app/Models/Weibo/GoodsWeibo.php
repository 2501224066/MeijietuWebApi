<?php


namespace App\Models\Weibo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Currency\Region;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

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
}