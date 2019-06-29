<?php


namespace App\Http\Controllers\Api;


use App\Models\Up\Runwater;
use Tymon\JWTAuth\Facades\JWTAuth;

class RunwaterController extends BaseController
{
    /**
     * 流水记录
     * @return mixed
     */
    public function runwaterList()
    {
        $uid = JWTAuth::user()->uid;

        $re = Runwater::where('from_uid', $uid)
            ->orWhere('to_uid', $uid)
            ->orderBy('created_at', 'DESC')
            ->where('status', Runwater::STATUS['成功'])
            ->paginate(50);

        return $this->success($re);
    }
}