<?php


namespace App\Http\Controllers\v1;


use \App\Http\Requests\Demand as DemandRequests;
use App\Models\Dt\Demand;
use App\Models\User;
use App\Service\Pub;
use Mockery\Exception;

class DemandController extends BaseController
{
    /**
     * 拒绝需求
     * @param DemandRequests $request
     */
    public function refuseDemand(DemandRequests $request)
    {
        // 身份必须为媒体主
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 需求数据
        $demand = Demand::whereDemandId($request->demand_id)->first();
        // 状态需为等待
        Pub::checkParm($demand->status, Demand::STATUS['等待'], '需求状态错误');
        // 修改
        $demand->status = Demand::STATUS['拒绝'];
        if (!$demand->save()) throw new Exception('操作失败');

        $this->success();
    }
}