<?php


namespace App\Http\Requests;


use App\Rules\SpecialChar;

class Salesman extends Base
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
            // 服务用户搜索
            case 'serveUserSelect':
                $rules['identity'] = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['user_num'] = ['nullable', 'present', new SpecialChar];
                $rules['phone']    = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['nickname'] = ['nullable', 'present', new SpecialChar,];
                break;

            // 服务商品搜索
            case 'serveGoodsSelect':
                $rules['user_num']      = ['nullable', 'present', new SpecialChar];
                $rules['goods_num']     = ['nullable', 'present', new SpecialChar];
                $rules['verify_status'] = ['nullable', 'present', new SpecialChar, 'numeric'];
                break;

            // 服务订单搜索
            case 'serveIndentSelect':
                $rules['buyer_num']         = ['nullable', 'present', new SpecialChar];
                $rules['seller_num']        = ['nullable', 'present', new SpecialChar];
                $rules['indent_num']        = ['nullable', 'present', new SpecialChar];
                $rules['bargaining_status'] = ['nullable', 'present', new SpecialChar, 'numeric'];
                break;

            // 商品审核
            case 'goodsVerify':
                $rules['goods_num']     = ['required', new SpecialChar];
                $rules['verify_status'] = ['required', new SpecialChar, 'numeric'];
                $rules['verify_cause']  = ['nullable', 'present', new SpecialChar];
                break;

            // 订单议价
            case 'indentBargaining':
                $rules['indent_num']    = ['required', new SpecialChar];
                $rules['seller_income'] = ['required', new SpecialChar, 'numeric'];
                break;

            // 软文商品设置价格
            case 'setPrice':
                $rules['goods_num'] = ['required', new SpecialChar];
                $rules['price']     = ['required', new SpecialChar, 'numeric'];
                break;

            // 需求失效
            case 'invalidDemand':
                $rules['demand_num'] = ['required', new SpecialChar, 'exists:dt_demand,demand_num'];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => '有必填参数未填写',
            'present'  => '参数缺失',
            'numeric'  => '参数格式需为数字',
            'json'     => '参数格式需为json',

            'demand_num.required' => '需求不得为空',
            'demand_num.exists' => '需求不存在'
        ];
    }
}