<?php


namespace App\Http\Requests;


class Goods extends Base
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
            // 创建商品
            case 'createSoftarticleGoods':
                $rules['title']                = 'required|unique:nb_goods,title';
                $rules['title_about']          = 'required';
                $rules['max_title_long']       = 'present';
                $rules['qq_ID']                = 'required|numeric';
                $rules['weixin_ID']            = 'present';
                $rules['room_ID']              = 'present';
                $rules['fans_num']             = 'present';
                $rules['news_source_status']   = 'present';
                $rules['entry_status']         = 'present';
                $rules['included_sataus']      = 'present';
                $rules['link']                 = 'present';
                $rules['link_type']            = 'present';
                $rules['weekend_status']       = 'present';
                $rules['reserve_status']       = 'present';
                $rules['remarks']              = 'present';
                $rules['modular_id']           = 'required|unique:tb_modular,modular_id';
                $rules['theme_id']             = 'required|unique:tb_theme,theme_id';
                $rules['filed_id']             = 'present|unique:tb_filed,filed_id';
                $rules['platform_id']          = 'present';
                $rules['industry_id']          = 'present';
                $rules['region_id']            = 'present';
                $rules['phone_weightlevel_id'] = 'present';
                $rules['pc_weightlevel_id']    = 'present';
                $rules['price_json']           = 'required|json';
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'present' => '缺少参数',

            'goods_title.required'       => '商品标题不得为空',
            'goods_title.unique'         => '商品标题已存在',
            'goods_title_about.required' => '标题简介不得为空',

            'qq_ID.required' => 'QQ号不得为空',
            'qq_ID.numeric'  => 'QQ号需为数字',

            'modular_id.required' => '模块不得为空',
            'modular_id.numeric'  => '模块需为数字',
            'modular_id.exists'   => '模块不存在',

            'theme_id.required' => '主题不得为空',
            'theme_id.numeric'  => '主题需为数字',
            'theme_id.exists'   => '主题不存在',

            'filed_id.required' => '领域不得为空',
            'filed_id.numeric'  => '领域需为数字',
            'filed_id.exists'   => '领域不存在',

            'price_data.required' => '价格信息不得为空',
            'price_data.json'     => '价格信息非JSON格式',
        ];
    }
}