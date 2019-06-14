<?php


namespace App\Http\Controllers\Api;

use App\Models\Tb\Modular;
use Illuminate\Support\Facades\Cache;

class GoodsAttributeController extends BaseController
{

    public function getGoodsAttribute()
    {
        /*if (Cache::has('goodsAttribute'))
            return $this->success(json_decode(Cache::get('goodsAttribute'), false));*/

        // 获取模块
        $re = Modular::with(['theme.filed' => function ($query) {
            $query->orderBy('filed_id', 'ASC');
        }])
            ->with(['theme.platform' => function ($query) {
                $query->orderBy('platform_id', 'ASC');
            }])
            ->with(['theme.industry' => function ($query) {
                $query->orderBy('industry_id', 'ASC');
            }])
            ->with(['theme.priceclassify' => function ($query) {
                $query->orderBy('priceclassify_id', 'ASC');
            }])
            ->with(['theme.region' => function ($query) {
                $query->orderBy('region_id', 'ASC');
            }])
            ->with(['theme.fansnumlevel' => function ($query) {
                $query->orderBy('fansnumlevel_id', 'ASC');
            }])
            ->with(['theme.readlevel' => function ($query) {
                $query->orderBy('readlevel_id', 'ASC');
            }])
            ->with(['theme.likelevel' => function ($query) {
                $query->orderBy('likelevel_id', 'ASC');
            }])
            ->with(['theme.pricelevel' => function ($query) {
                $query->orderBy('pricelevel_id', 'ASC');
            }])
            ->get();

        //Cache::put('goodsAttribute', json_encode($re), 30);

        return $this->success($re);
    }
}