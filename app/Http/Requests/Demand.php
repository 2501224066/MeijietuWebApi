<?php


namespace App\Http\Requests;


use App\Rules\SpecialChar;

class Demand extends Base
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];

        switch ($this->getFunName()) {
            // 拒绝需求
            case 'refuseDemand':
                $rules['demand_num'] = ['required', new SpecialChar, 'exists:dt_demand, demand_num'];
                break;
            // 接受需求
            case 'acceptDemand':
                $rules['demand_num'] = ['required', new SpecialChar, 'exists:dt_demand, demand_num'];
                break;
            // 完成需求
            case 'completeDemand':
                $rules['demand_num'] = ['required', new SpecialChar, 'exists:dt_demand, demand_num'];
                $rules['back_link'] = ['required'];
                break;
        }

        return $rules;
    }


    public function messages()
    {
        return [
            'demand_num.required' => '需求不得为空',
            'demand_num.exists'   => '需求不存在',
            'back_link'          => '链接不得为空'
        ];
    }
}