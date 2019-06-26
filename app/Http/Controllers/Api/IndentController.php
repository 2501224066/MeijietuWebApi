<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Indent as IndentRequests;
use App\Models\Indent\IndentInfo;
use App\Models\User;

class IndentController extends BaseController
{
    /**
     * 创建订单
     * @param IndentRequests $request
     * @return mixed
     */
    public function createIndent(IndentRequests $request)
    {
        // 检查身份
        User::checkIdentity(User::IDENTIDY['广告主']);
        // json转对象
        $info = json_decode($request->info, true);
        // 数据整理
        $data = IndentInfo::dataSorting($info);
        // 添加
        IndentInfo::add($data);

        return $this->success();
    }
}