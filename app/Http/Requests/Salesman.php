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
            case 'setSoftArticlePrice':
                $rules['goods_num'] = ['required', new SpecialChar];
                $rules['price']     = ['required', new SpecialChar, 'numeric'];
                break;

            // 创建套餐池
            case 'createMealPool':
                $rules['goods_id_json'] = ['required', new SpecialChar, 'json'];
                $rules['pool_name']     = ['required', new SpecialChar];
                break;

            // 软文套餐创建需求
            case 'softArticleMealCreateDemand':
                $rules['goods_id_json'] = ['required', new SpecialChar, 'json'];
                $rules['indent_num']    = ['required', new SpecialChar, 'exists:indent_info,indent_num'];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'present' => '参数不全',
            'numeric' => '参数格式错误',
            'json'    => '参数格式错误',

            'indent_num.exists' => '订单不存在'
        ];
    }
}