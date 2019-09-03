<?php

namespace App\Http\Requests;

use App\Rules\SpecialChar;
use Illuminate\Http\Request;

class Auth extends Base
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

    public function rules()
    {
        $rules = [];

        switch ($this->getFunName()) {
            // 检查手机号
            case 'checkPhone':
                $rules['type']    = ['required', new SpecialChar];
                $rules['smsCode'] = ['required', new SpecialChar];
                switch (Request::input('type')) {
                    case 1: // 手机号必须未注册【注册】
                        $rules['phone'] = ['required', new SpecialChar, 'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/', 'unique:user,phone'];
                        break;

                    case 2: // 手机号必须已注册【重置密码】
                        $rules['phone'] = ['required', new SpecialChar, 'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/', 'exists:user,phone'];
                        break;
                }
                break;

            // 注册
            case 'register':
                $rules['phone']                 = ['required', new SpecialChar, 'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/', 'unique:user,phone'];
                $rules['email']                 = ['required', 'email', 'unique:user,email'];
                $rules['password']              = ['required', 'between:6,18'];
                $rules['password_confirmation'] = ['required', 'same:password'];
                $rules['nickname']              = ['required', new SpecialChar, 'between:3,10'];
                $rules['nextToken']             = ['required'];
                $rules['identity']              = ['required', 'numeric'];
                $rules['salesman_id']           = ['nullable', 'present', 'numeric', 'exists:user,uid'];
                $rules['agent_domain']          = ['nullable', 'present', 'active_url'];
                break;

            // 登录
            case 'signIn':
                $rules['phone']    = ['required', new SpecialChar, 'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/', 'exists:user,phone'];
                $rules['password'] = ['required', new SpecialChar, 'between:6,18'];
                $rules['imgCode']  = ['required', new SpecialChar];
                $rules['imgToken'] = ['required', new SpecialChar];
                break;

            // 动态登录
            case 'codeSignIn':
                $rules['phone']   = ['required', new SpecialChar, 'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/', 'exists:user,phone'];
                $rules['smsCode'] = ['required', new SpecialChar, 'numeric'];
                break;

            // 重置密码
            case 'resetPass':
                $rules['phone']                 = ['required', new SpecialChar, 'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/', 'exists:user,phone'];
                $rules['password']              = ['required', new SpecialChar, 'between:6,18', 'confirmed'];
                $rules['password_confirmation'] = ['required', new SpecialChar];
                $rules['nextToken']             = ['required', new SpecialChar];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'active_url'           => '非有效链接',
            'password_confirmation.same' => '两次输入密码不一致',

            'phone.required' => '手机号不得为空',
            'phone.regex'    => '手机号无效',
            'phone.unique'   => '手机号已被使用',
            'phone.exists'   => '手机号未注册',

            'email.required'     => '邮箱不得为空',
            'email.email'        => '邮箱格式错误',
            'email.unique'       => '邮箱已被使用',
            'salesman_id.exists' => '客服不存在',

            'password.required'              => '密码不得为空',
            'password.confirmed'             => '两次输入密码不一致',
            'password.between'               => '密码长度需大于' . ':min' . '位，小于' . ':max' . '位',
            'password_confirmation.required' => '重复密码不得为空',

            'nickname.required' => '昵称不得为空',
            'nickname.between'  => '昵称长度需大于' . ':min' . '位，小于' . ':max' . '位',
            'identity.required' => '身份不得为空',
            'identity.numeric'  => '身份不合规',

            'smsCode.required'   => '验证码不得为空',
            'imgCode.required'   => '验证码不得为空',
            'smsCode.numeric'    => '验证码必须为数字',
            'nextToken.required' => '令牌不得为空',
            'imgToken.required'  => '令牌不得为空',
        ];
    }
}