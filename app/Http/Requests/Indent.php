<?php


namespace App\Http\Requests;


use Illuminate\Support\Facades\Request;
use Mockery\Exception;

class Indent extends Base
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
            // 生成订单
            case 'createMarketIndent':
                $rules['indent_items_json'] = ['required', 'json', function () {
                    $arr = json_decode(Request::input('indent_items_json'));
                    foreach ($arr as $v)
                        if (!($v->goods_id && $v->modular_type && $v->priceclassify_id))
                            throw new Exception('信息不全');
                }];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'indent_item_json.required' => '订单项目不得为空',
            'indent_item_json.json'     => '数据非JSON格式',
        ];
    }
}