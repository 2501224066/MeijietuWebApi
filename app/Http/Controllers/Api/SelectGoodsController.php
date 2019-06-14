<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\SelectGoods as SelectGoodsRequests;
use App\Models\Selfmedia\GoodsSelfmediaPrice;
use App\Models\Softarticle\GoodsSoftarticlePrice;
use App\Models\Weixin\GoodsWeixin;
use App\Models\Weixin\GoodsWeixinPrice;
use App\Models\Weibo\GoodsWeibo;
use App\Models\Weibo\GoodsWeiboPrice;
use App\Models\Video\GoodsVideo;
use App\Models\Video\GoodsVideoPrice;
use App\Models\Selfmedia\GoodsSelfmedia;
use App\Models\Softarticle\GoodsSoftarticle;
use App\Service\ModularData;
use Tymon\JWTAuth\Facades\JWTAuth;

class SelectGoodsController extends BaseController
{
    /**
     * 搜索微信商品
     * @param SelectGoodsRequests $request
     * @return mixed
     */
    public function selectWeixinGoods(SelectGoodsRequests $request)
    {
        // 价格筛选
        $idArr = GoodsWeixinPrice::screenPrice($request);

        // 拼装条件并查询
        $data = GoodsWeixin::select($request, $idArr);

        // 插入价格信息
        $re = GoodsWeixinPrice::withPriceInfo($data);

        return $this->success($re);
    }

    /**
     * 搜索微博商品
     * @param SelectGoodsRequests $request
     * @return mixed
     */
    public function selectWeiboGoods(SelectGoodsRequests $request)
    {
        // 价格筛选
        $idArr = GoodsWeiboPrice::screenPrice($request);

        // 拼装条件并查询
        $data = GoodsWeibo::select($request, $idArr);

        // 插入价格信息
        $re = GoodsWeiboPrice::withPriceInfo($data);

        return $this->success($re);
    }

    /**
     * 搜索视频商品
     * @param SelectGoodsRequests $request
     * @return mixed
     */
    public function selectVideoGoods(SelectGoodsRequests $request)
    {
        // 价格筛选
        $idArr = GoodsVideoPrice::screenPrice($request);

        // 拼装条件并查询
        $data = GoodsVideo::select($request, $idArr);

        // 插入价格信息
        $re = GoodsVideoPrice::withPriceInfo($data);

        return $this->success($re);
    }

    /**
     * 搜索自媒体商品
     * @param SelectGoodsRequests $request
     * @return mixed
     */
    public function selectSelfmediaGoods(SelectGoodsRequests $request)
    {
        // 价格筛选
        $idArr = GoodsSelfmediaPrice::screenPrice($request);

        // 拼装条件并查询
        $data = GoodsSelfmedia::select($request, $idArr);

        // 插入价格信息
        $re = GoodsSelfmediaPrice::withPriceInfo($data);

        return $this->success($re);
    }

    /**
     * 搜索软文商品
     * @param SelectGoodsRequests $request
     * @return mixed
     */
    public function selectSoftarticleGoods(SelectGoodsRequests $request)
    {

        // 价格筛选
        $idArr = GoodsSoftarticlePrice::screenPrice($request);

        // 拼装条件并查询
        $data = GoodsSoftarticle::select($request, $idArr);

        // 插入价格信息
        $re = GoodsSoftarticlePrice::withPriceInfo($data);

        return $this->success($re);
    }

    /**
     * 搜索用户创建的全部商品
     * @return mixed
     */
    public function userGoods()
    {
        $uid = JWTAuth::user()->uid;
        $re  = [];

        // 微信商品
        $re['weixin']       = ModularData::goodsInfo('WEIXIN', $uid, 'uid');
        // 微博商品
        $re['weibo']        = ModularData::goodsInfo('WEIBO', $uid, 'uid');
        // 视频商品
        $re['video']        = ModularData::goodsInfo('VIDEO', $uid, 'uid');
        // 自媒体商品
        $re['selfmedia']    = ModularData::goodsInfo('SELFMEDIA', $uid, 'uid');
        // 软文商品
        $re['softarticle']  = ModularData::goodsInfo('SOFTARTICLE', $uid, 'uid');

        return $this->success($re);
    }

    /**
     * 单个商品信息
     * @param SelectGoodsRequests $request
     * @return mixed
     */
    public function oneGoodsInfo(SelectGoodsRequests $request)
    {
        // 检查模块类型
        ModularData::checkModularType($request->modular_type);
        // 检查商品是否存在
        ModularData::checkGoodsHas($request->modular_type, $request->goods_id);
        // 商品信息
        $goods = ModularData::modularTypeToGetGoodsTableClass($request->modular_type)::where('goods_id', $request->goods_id)
            ->first();
        // 商品价格信息
        $goods->price_info = ModularData::modularTypeToGetGoodsPriceTableClass($request->modular_type)::where('goods_id', $request->goods_id)
            ->get();

        return $this->success($goods);
    }
}