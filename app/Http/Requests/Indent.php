<?php


namespace App\Http\Requests;


class Indent extends Base
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
            // 创建订单
            case 'createIndent':
                $rules['info'] = 'required|json';
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => '参数不全',
            'json' => '格式错误'
        ];
    }
}