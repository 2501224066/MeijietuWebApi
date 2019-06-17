<?php


namespace App\Http\Controllers\Api;

use App\Models\Nb\Goods;
use App\Models\Tb\Modular;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Goods as GoodsRequests;

class GoodsController extends BaseController
{

    /**
     * 获取商品属性
     * @return mixed
     */
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
            ->with(['theme.weightlevel' => function ($query) {
                $query->orderBy('weightlevel_id', 'ASC');
            }])
            ->get();

        //Cache::put('goodsAttribute', json_encode($re), 30);

        return $this->success($re);
    }

    /**
     * 创建商品
     * @param GoodsRequests $request
     * @return mixed
     * @throws \Throwable
     */
    public function createGoods(GoodsRequests $request)
    {
        // 组装数组
        $arr = Goods::assembleArr($request);
        // 添加商品
        $goodsId = Goods::add($arr, $request->price_json);
        // 补充基础数据
        Goods::addBasicsData($goodsId, $arr);

        return $this->success();
    }

    /**
     * 个人所有商品
     * @return mixed
     */
    public function goodsBelongToUser()
    {
        $re = Goods::getUserGoods();

        return $this->success($re);
    }

    /**
     * 搜索商品
     * @param GoodsRequests $request
     * @return mixed
     */
    public function selectGoods(GoodsRequests $request)
    {
        $re = Goods::getGoods($request);

        return $this->success($re);
    }
}