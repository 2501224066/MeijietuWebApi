<?php


namespace App\Http\Requests;


class Shopcart
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
            // 加入购物车
            case 'joinShopcart':
                $rules['goods_id_json'] = 'required|json';
                break;

            // 删除购物车商品
            case 'delShopcart':
                $rules['goods_id_json'] = 'required|json';
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