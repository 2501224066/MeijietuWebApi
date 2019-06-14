<?php


namespace App\Http\Controllers\Api;

use App\Models\Tb\Modular;
use Illuminate\Support\Facades\Cache;

class GoodsAttributeController extends BaseController
{

    public function getGoodsAttribute()
    {
        if (Cache::has('goodsAttribute'))
            return $this->success(json_decode(Cache::get('goodsAttribute'), false));

        // 获取模块
        $re = Modular::with('theme.filed')
            ->with('theme.platform')
            ->with('theme.industry')
            ->with('theme.priceclassify')
            ->with('theme.region')
            ->with('theme.fansnumlevel')
            ->with('theme.readlevel')
            ->with('theme.likelevel')
            ->with('theme.pricelevel')
            ->get();

        Cache::put('goodsAttribute', json_encode($re), 30);

        return $this->success($re);
    }
}