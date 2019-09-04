<?php

namespace App\Http\Middleware;

use App\Models\System\Setting;
use App\Server\Pub;
use Closure;

class BanRouter
{
    /**
     * 禁用接口
     */
    public function handle($request, Closure $next)
    {
        $ban_router = Setting::whereSettingName('ban_router')->value('value');

        // * 表示全部禁用
        if ($ban_router == '*') {
            return response()->json([
                'status_code' => 500,
                'message'     => '非常抱歉，此功能正在维护中',
                'data'        => []], 500);
        } else {
            $ban_router = explode(';', $ban_router);
            $r = Pub::routerToFunc();
            if (in_array($r, $ban_router)) {
                return response()->json([
                    'status_code' => 500,
                    'message'     => '非常抱歉，此功能正在维护中',
                    'data'        => []], 500);
            }
        }

        return $next($request);
    }
}
