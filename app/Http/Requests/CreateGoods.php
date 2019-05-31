<?php


namespace App\Http\Requests;


class CreateGoods extends Base
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

        switch ($this->getFunName())
        {
            // 创建微信商品
            case 'createWeixinGoods':
                $rules['theme_id'] = 'required|numeric';
                $rules['goods_title'] = 'required';
                $rules['goods_title_about'] = 'required';
                $rules['weixin_ID'] = 'required';
                $rules['filed_id'] = 'required|numeric';
                $rules['fans_num'] = 'required|numeric';
                $rules['region_id'] = 'required|numeric';
                $rules['remarks'] = 'present';
                $rules['qq_ID'] = 'required|numeric';
                $rules['price_data'] = 'required';
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return  [
            'theme_id.required' => '主题不得为空',
            'goods_title.required' => '商品标题不得为空',
            'goods_title_about.required' => '标题简介不得为空',
            'weixin_ID.required' => '微信号不得为空',
            'filed_id.required' => '领域不得为空',
            'fans_num.required' => '粉丝数量不得为空',
            'region_id.required' => '面向地区不得为空',
            'qq_ID.remarks' => 'QQ号不得为空',
            'price_data.required' => '价格信息不得为空',
            'theme_id.numeric' => '主题需为数字',
            'filed_id.numeric' => '领域需为数字',
            'qq_ID.numeric' => 'QQ号需为数字',
            'region_id.numeric' => '面向地区需为数字',
        ];
    }
}