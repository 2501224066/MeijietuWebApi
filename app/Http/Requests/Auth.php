<?php

namespace App\Http\Requests;

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

        switch ($this->getFunName())
        {
            case 'checkPhone':
                $rules['phone'] = ['required','regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/','unique:user,phone'];
                $rules['smsCode'] = 'required|numeric';
                break;

            case 'register':
                $rules['phone'] = ['required','regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/','unique:user,phone'];
                $rules['email'] = 'required|email|unique:user,email';
                $rules['password'] = 'required|between:6,18';
                $rules['password_confirmation'] = 'required|same:password';
                $rules['nickname'] = 'required|between:3,10';
                $rules['token'] = 'required';
                break;

            case 'signIn':
                $rules['phone'] = ['required','regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/','exists:user,phone'];
                $rules['password'] = 'required|between:6,18';
                $rules['imgCode'] = 'required';
                $rules['imgToken'] = 'required';
                break;

            case 'codeSignIn':
                $rules['phone'] = ['required','regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/','exists:user,phone'];
                $rules['smsCode'] = 'required|numeric';
                break;

           /* case 'resetPass':
                $rules['email'] = 'required|email|exists:user,email';
                $rules['password'] = 'required|between:6,18';
                $rules['password_confirmation'] = 'required|same:password';
                $rules['code'] = 'required';
                break;*/
        }

        return $rules;
    }

    public function messages() 
    {        
        return  [
            'phone.required' => '手机号不得为空',
            'phone.regex' => '手机号无效',
            'phone.unique'  => '手机号已被使用',

            'email.required' => '邮箱不得为空',
            'email.email' => '邮箱格式错误',
            'email.unique'  => '邮箱已被使用',
            'phone.exists' => '手机号未注册',

            'password.required' => '密码不得为空',
            'password_confirmation.required' =>'重复密码不得为空',
            'password_confirmation.same' => '两次输入密码不一致',
            'password.between' => '密码长度需大于'. ':min' . '位，小于' . ':max'. '位',
            'nickname.required' => '昵称不得为空',
            'nickname.between' => '昵称长度需大于'. ':min' . '位，小于' . ':max'. '位',
            'smsCode.required' => '验证码不得为空',
            'imgCode.required' => '验证码不得为空',
            'smsCode.numeric' => '验证码必须为数字',
            'token.required' => '令牌不得为空',
            'imgToken.required' => '令牌不得为空',
        ]; 
    }
}