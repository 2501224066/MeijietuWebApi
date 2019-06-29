<?php


namespace App\Http\Requests;


use App\Rules\SpecialChar;

class UserInfo extends Base
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
            // 个人实名认证
            case 'realnamePeople':
                $rules['truename']           = ['required', new SpecialChar];
                $rules['identity_card_ID']   = ['required', new SpecialChar];
                $rules['identity_card_face'] = ['required', new SpecialChar];
                $rules['identity_card_back'] = ['required', new SpecialChar];
                $rules['bank_deposit']       = ['required', new SpecialChar];
                $rules['bank_branch']        = ['required', new SpecialChar];
                $rules['bank_prov']          = ['required', new SpecialChar];
                $rules['bank_city']          = ['required', new SpecialChar];
                $rules['bank_card']          = ['required', new SpecialChar];
                $rules['bank_band_phone']    = ['required', new SpecialChar, 'numeric', 'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/'];
                $rules['smsCode']            = ['required', new SpecialChar, 'numeric'];
                break;

            // 企业实名认证
            case 'realnameEnterprise':
                $rules['enterprise_name']    = ['required', new SpecialChar];
                $rules['social_credit_code'] = ['required', new SpecialChar];
                $rules['business_license']   = ['required', new SpecialChar];
                $rules['bank_deposit']       = ['required', new SpecialChar];
                $rules['bank_branch']        = ['required', new SpecialChar];
                $rules['bank_prov']          = ['required', new SpecialChar];
                $rules['bank_city']          = ['required', new SpecialChar];
                $rules['bank_card']          = ['required', new SpecialChar];
                $rules['bank_band_phone']    = ['required', new SpecialChar, 'numeric', 'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/'];
                $rules['smsCode']            = ['required', new SpecialChar, 'numeric'];
                break;

            // 修改用户信息
            case 'saveInfo':
                $rules['head_portrait'] = ['required', new SpecialChar];
                $rules['nickname']      = ['required', new SpecialChar];
                $rules['sex']           = ['present'];
                $rules['birth']         = ['present'];
                $rules['qq_ID']         = ['present'];
                $rules['weixin_ID']     = ['present'];
                $rules['imgCode']       = ['required', new SpecialChar];
                $rules['imgToken']      = ['required', new SpecialChar];
                break;

            // 修改手机号
            case 'savePhone':
                $rules['phone']                  = ['required', new SpecialChar, 'numeric', 'exists:user,phone', 'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/'];
                $rules['smsCode']                = ['required', new SpecialChar, 'numeric'];
                $rules['new_phone']              = ['required', new SpecialChar, 'confirmed', 'numeric', 'unique:user,phone', 'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/'];
                $rules['new_phone_confirmation'] = ['required', new SpecialChar];
                break;

            // 修改密码
            case 'savePass':
                $rules['password']              = ['required', new SpecialChar, 'between:6,18'];
                $rules['smsCode']               = ['required', new SpecialChar, 'numeric'];
                $rules['new_pass']              = ['required', new SpecialChar, 'between:6,18', 'confirmed'];
                $rules['new_pass_confirmation'] = ['required', new SpecialChar];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'truename.required'           => "真实姓名不得为空",
            'identity_card_ID.required'   => "身份证号码不得为空",
            'identity_card_face.required' => "身份证正面照不得为空",
            'identity_card_back.required' => "身份证背面照不得为空",
            'bank_deposit.required'       => "开户行不得为空",
            'bank_branch.required'        => "开户支行不得为空",

            'enterprise_name.required' => "公司名称不得为空",
            'social_credit_code'       => '统一社会信用代码不得为空',
            'business_license'         => '营业执照不得为空',
            'bank_deposit'             => '开户银行不得为空',

            'bank_prov.required'       => "开户省不得为空",
            'bank_city.required'       => "开户市不得为空",
            'bank_card.required'       => "银行卡号不得为空",
            'bank_band_phone.required' => "银行卡绑定手机号不得为空",
            'bank_band_phone.regex'    => "手机号不合规",
            'smsCode.required'         => "验证码不得为空",
            'smsCode.numeric'          => "验证码必须为数字",

            "head_portrait"     => "头像不得为空",
            "nickname.required" => "昵称不得为空",
            "sex.numeric"       => "性别不合规",
            "qq_ID.numeric"     => "QQ号不合规",
            'imgCode.required'  => '验证码不得为空',
            'imgToken.required' => '令牌不得为空',

            'phone.required'                  => "手机号不得为空",
            'phone.numeric'                   => "手机号必须是数字",
            'phone.exists'                    => "手机号未注册",
            'phone.regex'                     => "手机号不合规",
            'new_phone.required'              => "新手机号不得为空",
            'new_phone.confirmed'             => "两次输入新手机号不一致",
            'new_phone.numeric'               => "新手机号必须是数字",
            'new_phone.unique'                => "新手机号已被注册",
            'new_phone.regex'                 => "新手机号不合规",
            'new_phone_confirmation.required' => "重复新手机号不得为空",

            'password.required'              => "密码不得为空",
            'password.between'               => '密码长度需大于' . ':min' . '位，小于' . ':max' . '位',
            'new_pass.required'              => "新密码不得为空",
            'new_pass.between:6,18'          => '新密码长度需大于' . ':min' . '位，小于' . ':max' . '位',
            'new_pass.confirmed'             => "两次输入新密码不一致",
            'new_pass_confirmation.required' => '重复新密码不得为空'
        ];
    }
}
