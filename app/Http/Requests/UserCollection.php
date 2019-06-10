<?php


namespace App\Http\Requests;


class UserCollection extends Base
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
            // 收藏商品
            case 'collectionGoods':
                $rules['goods_id'] = 'required|numeric';
                $rules['modular_type']     = 'required';
                break;

            // 删除收藏
            case 'delCollection':
                $rules['goods_id'] = 'required|numeric';
                $rules['modular_type']     = 'required';
                break;

            // 获取收藏
            case 'getCollection':
                $rules['modular_type']     = 'present';
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'goods_id.required' => "商品不得为空",
            'goods_id.numeric'  => "商品需为数字",
            'modular_type'      => "模块类型不得为空",
        ];
    }
}