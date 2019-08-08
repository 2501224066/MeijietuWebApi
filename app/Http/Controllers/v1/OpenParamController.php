<?php


namespace App\Http\Controllers\v1;


use App\Models\Data\Goods;
use App\Models\System\Information;
use App\Models\System\Setting;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class OpenParamController extends BaseController
{
    /**
     * 推荐商品
     * @return mixed
     */
    public function recommendGoods()
    {
        if (Cache::has('recommendGoods'))
            return $this->success(json_decode(Cache::get('recommendGoods')));

        $re = Goods::recommendGoods();

        Cache::put('recommendGoods', json_encode($re), 60 * 12);

        return $this->success($re);
    }

    /**
     * 资讯文章
     * @return mixed
     */
    public function information()
    {
        if (Cache::has('information'))
            return $this->success(json_decode(Cache::get('information')));

        $re = Information::indexPageInformation(3);

        Cache::put('information', json_encode($re), 60 * 12);

        return $this->success($re);
    }

    /**
     * 随机客服
     * @return mixed
     */
    public function randomSalesman()
    {
        $re = User::randomSalesman();

        return $this->success($re);
    }

    /**
     * banner图
     * @return mixed
     */
    public function banner()
    {
        if (Cache::has('banner'))
            return $this->success(json_decode(Cache::get('banner')));

        $re =  Setting::banner();

        Cache::put('banner', json_encode($re), 60 * 12);

        return $this->success($re);
    }
}