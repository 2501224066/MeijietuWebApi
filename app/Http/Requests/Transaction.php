<?php


namespace App\Http\Requests;


use App\Rules\SpecialChar;

class Transaction extends Base
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
            // 订单付款
            case 'indentPayment':
                $rules['indent_num'] = ['required', new SpecialChar];
                break;

            // 买家添加需求文档
            case 'addDemandFile':
                $rules['indent_num']  = ['required', new SpecialChar];
                $rules['demand_file'] = ['required', new SpecialChar];
                break;

            // 买家待接单取消订单
            case 'acceptIndentBeforeCancel':
                $rules['indent_num']  = ['required', new SpecialChar];
                break;

            // 卖家接单
            case 'acceptIndent':
                $rules['indent_num']  = ['required', new SpecialChar];
                break;

            // 交易中买家取消订单
            case 'inTransactionBuyerCancel':
                $rules['indent_num']  = ['required', new SpecialChar];
                break;

            // 交易中卖家取消订单
            case 'inTransactionSellerCancel':
                $rules['indent_num']  = ['required', new SpecialChar];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'indent_num.required'  => '订单编号不得为空',
            'demand_file.required' => '需求文件不得为空'
        ];
    }
}