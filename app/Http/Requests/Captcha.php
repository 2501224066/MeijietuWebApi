<?php

namespace App\Http\Requests;

class Captcha extends Base
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
            // 获取邮箱验证码
            case 'emailVerifCode':
                $rules['email'] = 'required|email';
                $rules['code_type'] = 'required';
                break;

            // 获取短信验证码
            case 'smsVerifCode':
                $rules['phone'] = ['required','regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/'];
                $rules['code_type'] = 'required';
                break;

            // 获取图片验证码
            case 'getImgCode':
                $rules['imgToken'] = 'required';
                break;

            // 检查图片验证码
            case 'checkImgCode':
                $rules['imgToken'] = 'required';
                $rules['imgCode'] = 'required';
                break;
        }

        return $rules;
    }

    public function messages() 
    {        
        return  [
            'email.required' => '邮箱不得为空',
            'email.email' => '邮箱格式错误',
            'email.unique'  => '邮箱已被使用',
            'phone.required' => '手机号不得为空',
            'phone.regex' => '手机号无效',
            'code_type.required' => '请求验证码类型不得为空',
            'imgToken.required' => '令牌不得为空',
            'imgCode.required' => '验证码不得为空',
        ]; 
    }
}