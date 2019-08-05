<?php


namespace App\Http\Requests;


use App\Rules\SpecialChar;

class News extends Base
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];

        switch ($this->getFunName()) {
            // 用户消息
            case 'newsBelongSelf':
                $rules['read_status'] = ['nullable', 'present', 'numeric'];;
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'present'  => '参数缺少',
        ];
    }
}