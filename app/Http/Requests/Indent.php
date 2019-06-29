<?php


namespace App\Http\Requests;


use App\Rules\SpecialChar;

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
                $rules['info'] = ['required', new SpecialChar, 'json'];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'info.required' => '商品信息不得为空',
            'info.json'     => '商品信息格式错误',
        ];
    }
}