<?php


namespace App\Http\Requests;


use App\Rules\SpecialChar;

class Collection
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
                $rules['goods_id_json'] = ['required', new SpecialChar, 'json'];
                break;

            // 删除收藏
            case 'delCollection':
                $rules['collection_id_json'] = ['required', new SpecialChar, 'json'];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => '有必填参数未填写',
            'json'     => '格式错误'
        ];
    }
}