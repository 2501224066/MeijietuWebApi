<?php


namespace App\Http\Controllers\v1;


use App\Http\Requests\MealPool as MealPoolRequests;
use App\Jobs\SoftArticleMealCreateDemandOP;
use App\Models\Data\MealPool;
use App\Models\User;

class MealPoolController extends BaseController
{
    /**
     * 创建套餐池
     * @param MealPoolRequests $request
     * @return mixed
     * @throws \Throwable
     */
    public function createMealPool(MealPoolRequests $request)
    {
        // 身份必须为业务员
        User::checkIdentity(User::IDENTIDY['业务员']);
        // json转array
        $goodIdArr = json_decode($request->goods_id_json, true);
        // 创建连接池操作
        MealPool::createMealPoolOP($goodIdArr, htmlspecialchars($request->pool_name));

        return $this->success();
    }

    /**
     * 软文套餐创建需求
     * @param MealPoolRequests $request
     * @return mixed
     */
    public function softArticleMealCreateDemand(MealPoolRequests $request)
    {
        // 身份必须为业务员
        User::checkIdentity(User::IDENTIDY['业务员']);
        // json转array
        $goodIdArr = json_decode($request->goods_id_json, true);
        // 创建需求
        SoftArticleMealCreateDemandOP::dispatch($request->indent_num, $goodIdArr)->onQueue('SoftArticleMealCreateDemandOP');

        return $this->success('稍等片刻，需求正在创建中');
    }

    /**
     * 套餐池列表
     * @return mixed
     */
    public function mealPoolList()
    {
       $pool = MealPool::wherePid(0)
           ->select('id','pool_name')
           ->paginate();

       foreach ($pool as $p)
       {
           $p->Children = MealPool::wherePid($p->id)
               ->get(['goods_id','title']);
       }

        return $this->success($pool);
    }
}