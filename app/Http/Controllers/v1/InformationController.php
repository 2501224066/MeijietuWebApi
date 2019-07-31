<?php


namespace App\Http\Controllers\v1;

use App\Http\Requests\Information as InformationRequests;
use App\Models\System\Information;

class InformationController extends BaseController
{
    /**
     * 资讯详情
     * @param InformationRequests $request
     * @return mixed
     */
    public function informationInfo(InformationRequests $request)
    {
        // 增加阅读量
        Information::addReadNum($request->information_id);
        // 文章
        $re['info'] = Information::whereInformationId($request->information_id)->first();
        // 上一篇
        $re['up'] = Information::where('information_id','<', $request->information_id)->first();
        // 下一篇
        $re['down'] = Information::where('information_id','>', $request->information_id)->first();

        return $this->success($re);
    }
}