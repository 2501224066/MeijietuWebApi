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

            // 消息内容
            case 'newsInfo':
                $rules['news_id'] = ['required', new SpecialChar, 'numeric'];
                break;

            // 消息已读
            case 'newsReaded':
                $rules['news_id_json'] = ['required', 'json'];
                break;

        }

        return $rules;
    }

    public function messages()
    {
        return [
            'present'  => '参数缺少',
            'required' => '参数缺少'
        ];
    }
}