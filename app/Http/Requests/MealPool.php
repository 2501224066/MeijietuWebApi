<?php


namespace App\Http\Requests;


use App\Rules\SpecialChar;

class MealPool extends Base
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];

        switch ($this->getFunName()) {
            // 创建套餐池
            case 'createMealPool':
                $rules['goods_id_json'] = ['required', new SpecialChar, 'json'];
                $rules['pool_name']     = ['required', new SpecialChar, 'unique:data_mealpool,pool_name'];
                break;

            // 软文套餐创建需求
            case 'softArticleMealCreateDemand':
                $rules['goods_id_json'] = ['required', new SpecialChar, 'json'];
                $rules['indent_num']    = ['required', new SpecialChar, 'exists:data_indent_info,indent_num'];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'json' => '参数格式错误',

            'indent_num.exists' => '订单不存在',
            'pool_name.unique'  => '池名称重复'
        ];
    }
}