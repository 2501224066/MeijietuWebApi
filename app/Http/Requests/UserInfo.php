<?php


namespace App\Http\Requests;


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

        switch ($this->getFunName())
        {
            // 个人实名认证
            case 'realnamePeople':
                $rules['truename'] = 'required';
                $rules['identity_card_ID'] = 'required';
                $rules['identity_card_face'] = 'required';
                $rules['identity_card_back'] = 'required';
                $rules['bank_deposit'] = 'required';
                $rules['bank_branch'] = 'required';
                $rules['bank_prov'] = 'required';
                $rules['bank_city'] = 'required';
                $rules['bank_card'] = 'required';
                $rules['bank_band_phone'] = ['required','Numeric','regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/'];
                $rules['smsCode'] = 'required|numeric';
                break;

            // 企业实名认证
            case 'realnameEnterprise':
                $rules['enterprise_name'] = 'required';
                $rules['social_credit_code'] = 'required';
                $rules['business_license'] = 'required';
                $rules['bank_deposit'] = 'required';
                $rules['bank_branch'] = 'required';
                $rules['bank_porv'] = 'required';
                $rules['bank_city'] = 'required';
                $rules['bank_card'] = 'required';
                $rules['bank_band_phone'] = ['required','Numeric','regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/'];
                $rules['smsCode'] = 'required|numeric';
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return  [
            'truename.required' => "真实姓名不得为空",
            'identity_card_ID.required' => "身份证号码不得为空",
            'identity_card_face.required' => "身份证正面照不得为空",
            'identity_card_back.required' => "身份证背面照不得为空",
            'bank_deposit.required' => "开户行不得为空",
            'bank_branch.required' => "开户支行不得为空",

            'enterprise_name.required' => "公司名称不得为空",
            'social_credit_code' => '统一社会信用代码不得为空',
            'business_license' => '营业执照不得为空',
            'bank_deposit' => '开户银行不得为空',

            'bank_prov.required' => "开户省不得为空",
            'bank_city.required' => "开户市不得为空",
            'bank_card.required' => "银行卡号不得为空",
            'bank_band_phone.required' => "银行卡绑定手机号不得为空",
            'bank_band_phone.regex' => "手机号不合规",
            'smsCode.required' => "验证码不得为空",
            'smsCode.numeric' => "验证码必须为数字",
        ];
    }
}
