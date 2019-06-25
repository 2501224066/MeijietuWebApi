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

            // 订单付款
            case 'indentPayment':
                $rules['indent_num'] = ['required', new SpecialChar];
                break;

            // 添加需求文档
            case 'addDemandFile':
                $rules['indent_num']  = ['required', new SpecialChar];
                $rules['demand_file'] = ['required', new SpecialChar];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => '参数不全',
            'json'     => '格式错误',

            'indent_num.required'  => '订单编号不得为空',
            'demand_file.required' => '需求文件不得为空'
        ];
    }
}