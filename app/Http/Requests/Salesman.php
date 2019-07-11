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
            // 服务订单搜索
            case 'serveGoodsSelect':
                $rules['uid']    = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['goods_num'] = ['nullable', 'present', new SpecialChar];
                break;

            // 服务订单搜索
            case 'serveIndentSelect':
                $rules['buyer_id']   = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['seller_id']  = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['indent_num'] = ['nullable', 'present', new SpecialChar];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'present'     => '参数不全',
            'uid.numeric' => '客户ID必须为数字',

            'buyer_id.numeric'  => '买家ID必须为数字',
            'seller_id.numeric' => '卖家ID必须为数字'
        ];
    }
}