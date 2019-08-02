<?php


namespace App\Http\Requests;


use App\Rules\SpecialChar;
use Mockery\Exception;

class Information extends Base
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];

        switch ($this->getFunName()) {
            // 资讯详情
            case 'informationInfo':
                $rules['information_id'] = ['required', new SpecialChar, 'numeric','exists:system_information,information_id'];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => '参数不全',
            'numeric'  => '参数格式错误',

            'information_id.exists' => '资讯已被删除'
        ];
    }
}