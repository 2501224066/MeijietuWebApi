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
            // 待付款删除订单
            case 'deleteIndentBeforePayment':
                $rules['indent_num'] = ['required', new SpecialChar, 'exists:indent_info,indent_num'];
                break;

            // 订单付款
            case 'indentPayment':
                $rules['indent_num'] = ['required', new SpecialChar, 'exists:indent_info,indent_num'];
                break;

            // 买家添加需求文档
            case 'addDemandFile':
                $rules['indent_num']  = ['required', new SpecialChar, 'exists:indent_info,indent_num'];
                $rules['demand_file'] = ['required', new SpecialChar];
                break;

            // 待接单取消订单
            case 'acceptIndentBeforeCancel':
                $rules['indent_num']   = ['required', new SpecialChar, 'exists:indent_info,indent_num'];
                $rules['cancel_cause'] = ['required', new SpecialChar];
                break;

            // 卖家接单
            case 'acceptIndent':
                $rules['indent_num'] = ['required', new SpecialChar, 'exists:indent_info,indent_num'];
                break;

            // 交易中买家取消订单
            case 'inTransactionBuyerCancel':
                $rules['indent_num']   = ['required', new SpecialChar, 'exists:indent_info,indent_num'];
                $rules['cancel_cause'] = ['required', new SpecialChar];
                break;

            // 交易中卖家取消订单
            case 'inTransactionSellerCancel':
                $rules['indent_num']   = ['required', new SpecialChar, 'exists:indent_info,indent_num'];
                $rules['cancel_cause'] = ['required', new SpecialChar];
                break;

            // 卖家确认完成
            case 'sellerConfirmComplete':
                $rules['indent_num'] = ['required', new SpecialChar, 'exists:indent_info,indent_num'];
                break;

            // 卖家添加成果文档
            case 'addAchievementsFile':
                $rules['indent_num']        = ['required', new SpecialChar, 'exists:indent_info,indent_num'];
                $rules['achievements_file'] = ['required', new SpecialChar];
                break;

            // 买家确认完成
            case 'buyerConfirmComplete':
                $rules['indent_num'] = ['required', new SpecialChar, 'exists:indent_info,indent_num'];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'indent_num.required'        => '订单编号不得为空',
            'indent_num.exists'          => '订单不存在',
            'demand_file.required'       => '需求文档不得为空',
            'achievements_file.required' => '成果文档不得为空',
            'cancel_cause.required'      => '取消原因不得为空'
        ];
    }
}