<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Indent as IndentRequests;
use App\Models\Indent\IndentInfo;

class IndentController extends BaseController
{
    /**
     * 创建订单
     * @param IndentRequests $request
     * @return mixed
     */
    public function createIndent(IndentRequests $request)
    {
        // json转对象
        $info = json_decode($request->info, true);
        // 数据整理
        $data = IndentInfo::dataSorting($info);
        // 添加
        IndentInfo::add($data);

        return $this->success();
    }

    /**
     * 查询用户订单
     * 订单有效期三天，过期订单加入删除队列
     */
}