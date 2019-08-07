<?php


namespace App\Http\Controllers\v1;


use \App\Http\Requests\Demand as DemandRequests;
use App\Jobs\DemandSettlement;
use App\Models\Data\Demand;
use App\Models\System\Setting;
use App\Models\User;
use App\Server\Pub;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class DemandController extends BaseController
{
    /**
     * 自己的需求
     * @return mixed
     */
    public function demandBelongSelf()
    {
        // 身份必须为媒体主
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 需求
        $re = Demand::whereUid(JWTAuth::user()->uid)
            ->where('status', '!=', Demand::STATUS['失效'])
            ->paginate();

        return $this->success($re);
    }

    /**
     * 拒绝需求
     * @param DemandRequests $request
     */
    public function refuseDemand(DemandRequests $request)
    {
        // 身份必须为媒体主
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 需求数据
        $demand = Demand::whereDemandNum($request->demand_num)->first();
        // 状态需为等待
        Pub::checkParm($demand->status, Demand::STATUS['等待'], '需求状态错误');
        // 修改
        $demand->status = Demand::STATUS['拒绝'];
        if (!$demand->save()) throw new Exception('操作失败');

        Log::info('【需求】 媒体主' . JWTFactory::user()->nickname . '拒绝需求', ['demand_id' => $request->demand_id]);
        $this->success();
    }

    /**
     * 接受需求
     * @param DemandRequests $request
     */
    public function acceptDemand(DemandRequests $request)
    {
        // 身份必须为媒体主
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 需求数据
        $demand = Demand::whereDemandNum($request->demand_num)->first();
        // 状态需为等待
        Pub::checkParm($demand->status, Demand::STATUS['等待'], '需求状态错误');
        // 修改
        $demand->status = Demand::STATUS['接受'];
        if (!$demand->save())
            throw new Exception('操作失败');

        Log::info('【需求】 媒体主' . JWTFactory::user()->nickname . '接受需求',
            ['demand_id' => $request->demand_id]);

        $this->success();
    }

    /**
     * 完成需求
     * @param DemandRequests $request
     */
    public function completeDemand(DemandRequests $request)
    {
        // 身份必须为媒体主
        User::checkIdentity(User::IDENTIDY['媒体主']);
        // 需求数据
        $demand = Demand::whereDemandNum($request->demand_num)->first();
        // 状态需为等待
        Pub::checkParm($demand->status, Demand::STATUS['接受'], '需求状态错误');
        // 修改
        $demand->status    = Demand::STATUS['完成'];
        $demand->back_link = htmlspecialchars($request->back_link);
        if (!$demand->save()) throw new Exception('操作失败');
        // 存入延迟队列结算
        $delayTime = Setting::whereSettingName('trans_payment_delay')->value('value');
        DemandSettlement::dispatch($request->demand_id)->onQueue('DemandSettlement')->delay($delayTime);

        Log::info('【需求】 媒体主' . JWTFactory::user()->nickname . '完成需求', [
            'demand_id' => $request->demand_id,
            'back_link' => $request->back_link]);
        $this->success();
    }
}