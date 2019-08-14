<?php


namespace App\Http\Controllers\v1;

use App\Http\Requests\Information as InformationRequests;
use App\Models\System\Information;
use Illuminate\Support\Facades\Cache;

class InformationController extends BaseController
{
    /**
     * 资讯及详情
     * @param InformationRequests $request
     * @return mixed
     */
    public function information(InformationRequests $request)
    {
        if ($request->information_id != null) {

            // 增加阅读量
            Information::addReadNum($request->information_id);
            // 文章
            $re['info'] = Information::whereInformationId($request->information_id)->first();
            // 上一篇
            $re['up'] = Information::where('information_id', '<', $request->information_id)->first();
            // 下一篇
            $re['down'] = Information::where('information_id', '>', $request->information_id)->first();

        } else {

            if (Cache::has('information'))
                return $this->success(json_decode(Cache::get('information')));

            $re = Information::indexPageInformation(3);

            Cache::put('information', json_encode($re), 60 * 12);
        }


        return $this->success($re);
    }
}