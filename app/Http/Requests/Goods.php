<?php


namespace App\Http\Requests;


use App\Models\Tb\Modular;
use Illuminate\Http\Request;

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
            case 'createGoods':
                $rules['title']       = 'required|unique:nb_goods,title';
                $rules['title_about'] = 'required';
                $rules['qq_ID']       = 'required|numeric';
                $rules['modular_id']  = 'required|numeric|exists:tb_modular,modular_id';
                $rules['theme_id']    = 'required|numeric|exists:tb_theme,theme_id';
                $rules['filed_id']    = 'required|numeric|exists:tb_filed,filed_id';
                $rules['remarks']     = 'present';
                $rules['price_json']  = 'required|json';

                // 定义规则
                switch (Modular::whereModularId(Request::input('modular_id'))->value('tag')) {
                    case 'WEIXIN':
                        $rules['weixin_ID']      = 'required';
                        $rules['fans_num']       = 'required|numeric';
                        $rules['reserve_status'] = 'required';
                        $rules['region_id']      = 'required|numeric|exists:tb_region,region_id';
                        break;

                    case 'WEIBO':
                        $rules['link']           = 'required';
                        $rules['reserve_status'] = 'required|numeric';
                        $rules['region_id']      = 'required|numeric|exists:tb_region,region_id';
                        break;

                    case 'VIDEO':
                        $rules['room_ID']     = 'required';
                        $rules['fans_num']    = 'required|numeric';
                        $rules['platform_id'] = 'required|numeric|exists:tb_platform,platform_id';
                        $rules['region_id']   = 'required|numeric|exists:tb_region,region_id';
                        break;

                    case 'SELFMEDIA':
                        $rules['reserve_status'] = 'required|numeric';
                        $rules['platform_id']    = 'required|numeric|exists:tb_platform,platform_id';
                        $rules['region_id']      = 'required|numeric|exists:tb_region,region_id';
                        break;

                    case 'SOFTARTICLE':
                        $rules['max_title_long']       = 'required|numeric';
                        $rules['news_source_status']   = 'required|numeric';
                        $rules['entry_status']         = 'required|numeric';
                        $rules['included_sataus']      = 'required|numeric';
                        $rules['link']                 = 'required';
                        $rules['link_type']            = 'required|numeric';
                        $rules['weekend_status']       = 'required|numeric';
                        $rules['platform_id']          = 'required|numeric|exists:tb_platform,platform_id';
                        $rules['industry_id']          = 'required|numeric|exists:tb_industry,industry_id';
                        $rules['region_id']            = 'required|numeric|exists:tb_region,region_id';
                        $rules['phone_weightlevel_id'] = 'required|numeric|exists:tb_weightlevel,weightlevel_id';
                        $rules['pc_weightlevel_id']    = 'required|numeric|exists:tb_weightlevel,weightlevel_id';
                        break;
                }

                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required'       => '商品标题不得为空',
            'title.unique'         => '商品标题已存在',
            'title_about.required' => '标题简介不得为空',

            'qq_ID.required' => 'QQ号不得为空',
            'qq_ID.numeric'  => 'QQ号需为数字',

            'modular_id.required' => '模块ID不得为空',
            'modular_id.numeric'  => '模块ID需为数字',
            'modular_id.exists'   => '模块ID不存在',

            'theme_id.required' => '主题ID不得为空',
            'theme_id.numeric'  => '主题ID需为数字',
            'theme_id.exists'   => '主题ID不存在',

            'filed_id.required' => '领域ID不得为空',
            'filed_id.numeric'  => '领域ID需为数字',
            'filed_id.exists'   => '领域ID不存在',

            'Platfrom_id.required' => '平台ID不得为空',
            'Platfrom_id.numeric'  => '平台ID需为数字',
            'Platfrom_id.exists'   => '平台ID不存在',

            'industry_id.required' => '行业ID不得为空',
            'industry_id.numeric'  => '行业ID需为数字',
            'industry_id.exists'   => '行业ID不存在',

            'region_id.required' => '地区ID不得为空',
            'region_id.numeric'  => '地区ID需为数字',
            'region_id.exists'   => '地区ID不存在',

            'price_json.required' => '价格信息不得为空',
            'price_json.json'     => '价格信息非JSON格式',

            'numeric' => '参数格式错误',
            'exists' => '参数不存在'
        ];
    }
}