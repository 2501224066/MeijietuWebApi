<?php


namespace App\Http\Requests;


use App\Rules\SpecialChar;
use Mockery\Exception;

class Pay extends Base
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
            // 充值
            case 'recharge':
                $rules['money'] = ['required', new SpecialChar, 'numeric', function($key,$value){
                    if($value <= 0)
                        throw new Exception('金额非法');
                }];
                break;

            // 提现
            case 'extract':
                $rules['money'] = ['required', new SpecialChar, 'numeric', function($key,$value){
                    if($value <= 0)
                        throw new Exception('金额非法');
                }];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'money.required' => '金额不得为空',
            'money.numeric'  => '金额必须为整数'
        ];
    }
}