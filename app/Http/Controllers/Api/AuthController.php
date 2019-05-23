<?php

namespace App\Http\Controllers\Api;

use App\Models\LogLogin;
use App\Models\User;
use App\Http\Requests\Auth as AuthRequests;
use App\Models\Captcha;
use Tymon\JWTAuth\Facades\JWTAuth;
use Hash;

class AuthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     * 要求附带email和password（数据来源users表）
     * 
     * @return void
     */
    public function __construct()
    {
        // 这里额外注意了：官方文档样例中只除外了『login』
        // 这样的结果是，token 只能在有效期以内进行刷新，过期无法刷新
        // 如果把 refresh 也放进去，token 即使过期但仍在刷新期以内也可刷新
        // 不过刷新一次作废
        //$this->middleware('auth:api', ['except' => ['login']]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }

    /**
     * 检查手机号
     */
    public function checkPhone(AuthRequests $request)
    {
        Captcha::checkCode($request->smsCode, $request->phone, 'checkPhone');
        $nextToken = Captcha::createAndKeepCode('nextToken', $request->phone, true);

        return $this->success(['nextToken' => $nextToken]);
    }

    /**
     * 注册
     */
    public function register(AuthRequests $request)
    {
        Captcha::checkCode($request->nextToken, $request->phone, 'nextToken');
        $user = User::add($request->phone, $request->email, $request->password, $request->nickname, $request->getClientIp());

        return $this->success([
            'phone' => $user->phone,
            'email' => $user->email,
            'nickname' => $user->nickname,
        ]);
        
    }

    /**
     * 账密登录
     * @param AuthRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn(AuthRequests $request)
    {
        $logId = LogLogin::write($request->phone, 1);
        //Captcha::checkCode($request->imgCode, $request->imgToken, 'imgCode');
        $user = User::checkLogin($request->phone, $request->password);
        $token = JWTAuth::fromUser($user);
        LogLogin::whereLogLoginId($logId)->update(['login_status' => 1]);

        return $this->respondWithToken($token,[
            'nickname' => $user->nickname,
            'identity' => $user->identity
        ]);
    }

    /**
     * 动态登录
     * @param AuthRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function codeSignIn(AuthRequests $request)
    {
        $logId = LogLogin::write($request->phone, 2);
        Captcha::checkCode($request->smsCode, $request->phone, 'codeSignIn');
        $user = User::wherePhone($request->phone)->first();
        $token = JWTAuth::fromUser($user);
        LogLogin::whereLogLoginId($logId)->update(['login_status' => 1]);

        return $this->respondWithToken($token,[
            'nickname' => $user->nickname,
            'identity' => $user->identity
        ]);
    }

    /**
     * 重置密码
     * @param AuthRequests $request
     * @return mixed
     */
    public function resetPass(AuthRequests $request)
    {
        Captcha::checkCode($request->nextToken, $request->phone, 'nextToken');
        User::saveInfo($request->phone, 'password', Hash::make($request->password) );

        return $this->success();
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signOut()
    {
        auth('api')->logout();
        return $this->success();
    }

    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $data = [])
    {
        $JWTAuthResponse = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];

        return response()->json(array_merge($data, $JWTAuthResponse));       
    }
}
