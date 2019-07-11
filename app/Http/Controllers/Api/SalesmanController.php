<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Service\Pub;
use Tymon\JWTAuth\Facades\JWTAuth;

class SalesmanController extends Controller
{
    /**
     * 服务用户列表
     */
    public function serveUserList()
    {
        $user = JWTAuth::user();
        // 身份必须为业务员
        User::checkIdentity(User::IDENTIDY['业务员']);
        //
    }
}