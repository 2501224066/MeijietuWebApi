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
                $rules['user_num']       = ['nullable', 'present', new SpecialChar];
                $rules['goods_num'] = ['nullable', 'present', new SpecialChar];
                break;

            // 服务订单搜索
            case 'serveIndentSelect':
                $rules['buyer_num']   = ['nullable', 'present', new SpecialChar];
                $rules['seller_num']  = ['nullable', 'present', new SpecialChar];
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
        ];
    }
}