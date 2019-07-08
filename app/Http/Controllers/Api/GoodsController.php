<?php


namespace App\Http\Controllers\Api;

use App\Jobs\GoodsCreatedOP;
use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;
use App\Models\Tb\Modular;
use App\Models\User;
use App\Service\Pub;
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
                $query->orderBy('fansnumlevel_min', 'ASC');
            }])
            ->with(['theme.readlevel' => function ($query) {
                $query->orderBy('readlevel_min', 'ASC');
            }])
            ->with(['theme.likelevel' => function ($query) {
                $query->orderBy('likelevel_min', 'ASC');
            }])
            ->with(['theme.pricelevel' => function ($query) {
                $query->orderBy('pricelevel_min', 'ASC');
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
        // 检查身份
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 组装数组
        $arr = Goods::assembleArr($request);
        // 价格数据
        $priceArr = json_decode($request->price_json, true);
        // 检查价格种类完整性
        GoodsPrice::checkPriceclassify($arr['theme_id'], $priceArr);
        // 添加商品
        $goodsId = Goods::add($arr, $priceArr);
        // 添加基础数据，删除制造商品
        GoodsCreatedOP::dispatch($goodsId, $arr)->onQueue('GoodsCreatedOP');

        return $this->success();
    }

    /**
     * 获取自己商品
     * @return mixed
     */
    public function goodsBelongSelf()
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 数据
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
        // 筛选价格
        $whereInGoodsIdArr = GoodsPrice::screePrice($request);

        $re = Goods::getGoods($request, $whereInGoodsIdArr);

        return $this->success($re);
    }

    /**
     * 单个商品信息
     * @param GoodsRequests $request
     * @return mixed
     */
    public function oneGoodsInfo(GoodsRequests $request)
    {
        $goodsId = Goods::whereGoodsNum($request->goods_num)->value('goods_id');
        $re = Goods::getGoods($request, [$goodsId]);

        return $this->success($re);
    }

    /**
     * 商品下架
     */
    public function goodsDown(GoodsRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 商品数据
        $goods = Goods::whereGoodsNum($request->goods_num)->first();
        // 检查状态
        Pub::checkStatus($goods->status, Goods::STATUS['上架']);
        // 下架
        Goods::down($goods);

        return $this->success();
    }
}