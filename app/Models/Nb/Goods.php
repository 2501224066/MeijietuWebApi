<?php


namespace App\Models\Nb;


use App\Jobs\addWeiXinBasicsData;
use App\Models\Tb\Filed;
use App\Models\Tb\Industry;
use App\Models\Tb\Modular;
use App\Models\Tb\Platform;
use App\Models\Tb\Priceclassify;
use App\Models\Tb\Region;
use App\Models\Tb\Theme;
use App\Models\Tb\Weightlevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class Goods extends Model
{
    protected $table = 'nb_goods';

    protected $primaryKey = 'goods_id';

    protected $guarded = [];

    /**
     * 组装数组
     * @param $request
     * @return array
     */
    public static function assembleArr($request)
    {
        $arr                 = [];
        $time                = date('Y-m-d H:i:s');
        $arr['title']        = htmlspecialchars($request->title);
        $arr['html_title']   = htmlspecialchars($request->title);
        $arr['title_about']  = htmlspecialchars($request->title_about);
        $arr['qq_ID']        = htmlspecialchars($request->qq_ID);
        $arr['modular_id']   = htmlspecialchars($request->modular_id);
        $modualrData         = Modular::whereModularId($request->modular_id)->first();
        $arr['modular_name'] = $modualrData->modular_name;
        $arr['theme_id']     = htmlspecialchars($request->theme_id);
        $arr['theme_name']   = Theme::whereThemeId($request->theme_id)->value('theme_name');
        $arr['filed_id']     = htmlspecialchars($request->filed_id);
        $arr['filed_name']   = Filed::whereFiledId($request->filed_id)->value('filed_id');
        $arr['remarks']      = htmlspecialchars($request->remarks);
        $arr['created_at']   = $time;
        $arr['updated_at']   = $time;
        $arr['goods_num']    = createGoodsNnm($modualrData->abbreviation);
        $arr['uid']          = JWTAuth::user()->uid;

        if ($request->has('weixin_ID'))
            $arr['weixin_ID'] = htmlspecialchars($request->weixin_ID);

        if ($request->has('fans_num'))
            $arr['fans_num'] = htmlspecialchars($request->fans_num);

        if ($request->has('link'))
            $arr['link'] = htmlspecialchars($request->link);

        if ($request->has('room_ID'))
            $arr['room_ID'] = htmlspecialchars($request->room_ID);

        if ($request->has('max_title_long'))
            $arr['max_title_long'] = htmlspecialchars($request->max_title_long);

        if ($request->has('news_source_status'))
            $arr['news_source_status'] = htmlspecialchars($request->news_source_status);

        if ($request->has('included_sataus'))
            $arr['included_sataus'] = htmlspecialchars($request->included_sataus);

        if ($request->has('link_type'))
            $arr['link_type'] = htmlspecialchars($request->link_type);

        if ($request->has('weekend_status'))
            $arr['weekend_status'] = htmlspecialchars($request->weekend_status);

        if ($request->has('entry_status'))
            $arr['entry_status'] = htmlspecialchars($request->entry_status);

        if ($request->has('phone_weightlevel_id')) {
            $arr['phone_weightlevel_id']  = htmlspecialchars($request->phone_weightlevel_id);
            $arr['phone_weightlevel_img'] = Weightlevel::whereWeightlevelId($request->phone_weightlevel_id)->value('img_path');
        }

        if ($request->has('pc_weightlevel_id')) {
            $arr['pc_weightlevel_id']  = htmlspecialchars($request->pc_weightlevel_id);
            $arr['pc_weightlevel_img'] = Weightlevel::whereWeightlevelId($request->pc_weightlevel_id)->value('img_path');
        }

        if ($request->has('reserve_status'))
            $arr['reserve_status'] = htmlspecialchars($request->reserve_status);

        if ($request->has('platform_id')) {
            $arr['platform_id']   = htmlspecialchars($request->platform_id);
            $platformData         = Platform::wherePlatformId($request->platform_id)->first();
            $arr['platform_name'] = $platformData->platform_name;
            $arr['platform_logo'] = $platformData->logo_path;
        }

        if ($request->has('industry_id')) {
            $arr['industry_id']   = htmlspecialchars($request->industry_id);
            $arr['industry_name'] = Industry::whereIndustryId($request->industry_id)->value('industry_name');
        }

        if ($request->has('region_id')) {
            $arr['region_id']   = htmlspecialchars($request->region_id);
            $arr['region_name'] = Region::whereRegionId($request->region_id)->value('region_name');
        }

        return $arr;
    }

    /**
     * 添加商品
     * @param array $arr 接收数据
     * @param string $priceJson 价格信息
     * @return string
     * @throws \Throwable
     */
    public static function add($arr, $priceJson)
    {
        $goodsId = '';
        DB::transaction(function () use ($arr, $priceJson, &$goodsId) {
            // 插入商品
            $goodsId = self::insertGetId($arr);
            if (!$goodsId)
                throw new Exception('保存失败');

            // 插入商品价格
            $priceArr = json_decode($priceJson, true);
            foreach ($priceArr as $priceclassify_id => $price) {
                $st = GoodsPrice::create([
                    'goods_id'           => $goodsId,
                    'priceclassify_id'   => $priceclassify_id,
                    'priceclassify_name' => Priceclassify::wherePriceclassifyId($priceclassify_id)->value('priceclassify_name'),
                    'price'              => $price
                ]);
                if (!$st)
                    throw new Exception('保存失败');
            }
        });

        return $goodsId;
    }

    /**
     * 补充基础数据
     * @param int $goodsId 商品id
     * @param array $arr 接收数据
     * @return bool
     */
    public static function addBasicsData($goodsId, $arr)
    {
        switch (Modular::whereModularId($arr['modular_id'])->value('tag')) {
            // 微信基础数据
            case 'WEIXIN':
                addWeiXinBasicsData::dispatch($goodsId, $arr['weixin_ID'])->onQueue('addWeiXinBasicsData');
                break;

            // 微博基础数据
            //TODO...
        }

        return true;
    }
}