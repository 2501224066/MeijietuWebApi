<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Indent as IndentRequests;
use App\Http\Requests\Indent;
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
        $indent_mum = IndentInfo::add($data);

        return $this->success(['indent_num' => $indent_mum]);
    }

    /**
     * 获取自己订单
     * @return mixed
     */
    public function indentBelongSelf()
    {
        // 订单数据
        $re = IndentInfo::getSelfIndent();

        return $this->success($re);
    }
}