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
                $rules['demand_id'] = ['required', new SpecialChar, 'exists:dt_demand, demand_id'];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'demand_id.required' => '需求不得为空',
            'demand_id.exists'   => '需求不存在'
        ];
    }
}