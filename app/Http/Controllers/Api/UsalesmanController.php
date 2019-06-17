<?php


namespace App\Http\Controllers\Api;

use App\Models\Usalesman\Usalesman;
use App\Models\Usalesman\UserUsalesman;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsalesmanController extends BaseController
{

    /**
     * 用户专属客服信息
     */
    public function usalsesmanInfo()
    {
        $uid = JWTAuth::user()->uid;
        // 获取客服ID
        $salesman_id = UserUsalesman::getSalesmanId($uid);
        // 获取客服信息
        $data = Usalesman::info($salesman_id);

        return $this->success($data);
    }

    /**
     * 分配客服
     */
    public function distributionUsalsesman()
    {
        $uid = JWTAuth::user()->uid;
        User::withUsalesman($uid);

        return $this->success('分配客服成功');
    }
}