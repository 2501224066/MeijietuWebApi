<?php


namespace App\Http\Controllers\v1;


use App\Models\Dt\Information;
use App\Models\Nb\Goods;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class IndexController extends BaseController
{
    /**
     * 首页
     * @return mixed
     */
    public function indexPage()
    {
//        if (Cache::has('indexPageData'))
//            return json_decode(Cache::get('indexPageData'));

        // banner
        $re['banner'] = SystemSetting::indexPageBanner();
        // 推荐商品
        $re['recommendGoods'] = Goods::indexPageRecommendGoods();
        // 随机客服
        $re['salesman'] = User::indexPageSalesman();
        // 资讯文章
        $re['information'] = Information::indexPageInformation(3);
        // ...

//      Cache::put('indexPageData', json_encode($re), 60 * 12);
        return $this->success($re);
    }
}