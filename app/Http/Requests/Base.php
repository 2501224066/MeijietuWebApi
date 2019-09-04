<?php

namespace App\Http\Requests;

use App\Server\Pub;
use Illuminate\Foundation\Http\FormRequest;

class Base extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 验证错误回调钩子
     */
    public function withValidator($validator)
    {
        if ($validator->fails()) {
            throw new \Exception($validator->messages()->first());
        }
    }

    /**
     * 获取路由指向方法名
     */
    public function getFunName() :string
    {
       return Pub::routerToFunc();
    }
}