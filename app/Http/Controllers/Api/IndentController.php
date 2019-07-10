<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Indent as IndentRequests;
use App\Http\Requests\Indent;
use App\Jobs\IndentCreatedOP;
use App\Models\Indent\IndentInfo;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        // 必须未拥有客服
        User::checkUserHasSalesman(JWTAuth::user()->uid,'y');
        // json转对象
        $info = json_decode($request->info, true);
        // 数据整理
        $data = IndentInfo::dataSorting($info);
        // 添加
        $indent_mum = IndentInfo::add($data);
        // 删除购物车中对应商品
        IndentCreatedOP::dispatch($info)->onQueue('IndentCreatedOP');

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