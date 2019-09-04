<?php

namespace App\Http\Middleware;

use Closure;

class ShieldSqlError
{
    /**
     * 屏蔽SQL报错
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ((!config('api.debug'))
            && isset($response->original['message'])
            && strstr($response->original['message'], 'SQLSTATE')) {

            return response()->json([
                'status_code' => 500,
                'message'     => '服务异常',
                'data'        => []], 500);
        }

        return $response;
    }
}
