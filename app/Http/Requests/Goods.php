<?php


namespace App\Http\Requests;


use App\Models\Attr\Modular;
use Illuminate\Http\Request;
use App\Rules\SpecialChar;

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
                $rules['title']       = ['required', new SpecialChar];
                $rules['title_about'] = ['required', new SpecialChar];
                $rules['qq_ID']       = ['required', 'numeric'];
                $rules['modular_id']  = ['required', 'numeric', 'exists:attr_modular,modular_id'];
                $rules['theme_id']    = ['required', 'numeric', 'exists:attr_theme,theme_id'];
                $rules['filed_id']    = ['required', 'numeric', 'exists:attr_filed,filed_id'];
                $rules['price_json']  = ['required', 'json'];
                $rules['remarks']     = ['nullable', 'present', new SpecialChar];
                $rules['avatar_url']  = ['nullable', 'present', new SpecialChar];

                // 定义规则
                switch (Modular::whereModularId(Request::input('modular_id'))->value('tag')) {
                    case Modular::TAG['微信营销']:
                        $rules['weixin_ID']      = ['required', new SpecialChar];
                        $rules['fans_num']       = ['required', 'numeric'];
                        $rules['reserve_status'] = ['required', new SpecialChar];
                        $rules['region_id']      = ['required', 'numeric', 'exists:attr_region,region_id'];
                        break;

                    case Modular::TAG['微博营销']:
                        $rules['link']           = ['required', 'active_url'];
                        $rules['auth_type']      = ['required', new SpecialChar, 'numeric'];
                        $rules['reserve_status'] = ['required', new SpecialChar, 'numeric'];
                        $rules['region_id']      = ['required', new SpecialChar, 'numeric', 'exists:attr_region,region_id'];
                        break;

                    case Modular::TAG['视频营销']:
                        $rules['room_ID']     = ['required', new SpecialChar];
                        $rules['fans_num']    = ['required', new SpecialChar, 'numeric'];
                        $rules['platform_id'] = ['required', new SpecialChar, 'numeric', 'exists:attr_platform,platform_id'];
                        $rules['region_id']   = ['required', new SpecialChar, 'numeric', 'exists:attr_region,region_id'];
                        break;

                    case Modular::TAG['自媒体营销']:
                        $rules['reserve_status'] = ['required', new SpecialChar, 'numeric'];
                        $rules['platform_id']    = ['required', new SpecialChar, 'numeric', 'exists:attr_platform,platform_id'];
                        $rules['region_id']      = ['required', new SpecialChar, 'numeric', 'exists:attr_region,region_id'];
                        break;

                    case Modular::TAG['软文营销']:
                        $rules['max_title_long']       = ['nullable', 'present', new SpecialChar, 'numeric'];
                        $rules['news_source_status']   = ['required', new SpecialChar, 'numeric'];
                        $rules['entry_status']         = ['required', new SpecialChar, 'numeric'];
                        $rules['included_sataus']      = ['required', new SpecialChar, 'numeric'];
                        $rules['link']                 = ['nullable', 'present', 'active_url'];
                        $rules['case_link']            = ['required', 'active_url'];
                        $rules['link_type']            = ['required', new SpecialChar, 'numeric'];
                        $rules['weekend_status']       = ['required', new SpecialChar, 'numeric'];
                        $rules['platform_id']          = ['required', new SpecialChar, 'numeric', 'exists:attr_platform,platform_id'];
                        $rules['industry_id']          = ['required', new SpecialChar, 'numeric', 'exists:attr_industry,industry_id'];
                        $rules['region_id']            = ['required', new SpecialChar, 'numeric', 'exists:attr_region,region_id'];
                        $rules['phone_weightlevel_id'] = ['required', new SpecialChar, 'numeric', 'exists:attr_weightlevel,weightlevel_id'];
                        $rules['pc_weightlevel_id']    = ['required', new SpecialChar, 'numeric', 'exists:attr_weightlevel,weightlevel_id'];
                        break;
                }
                break;

            // 搜索商品
            case 'selectGoods':
                $rules['modular_id']       = ['required', new SpecialChar, 'numeric', 'exists:attr_modular,modular_id'];
                $rules['theme_id']         = ['nullable', 'present', new SpecialChar, 'numeric', 'exists:attr_theme,theme_id'];
                $rules['key_word']         = ['nullable', 'present', new SpecialChar];
                $rules['filed_id']         = ['nullable', 'present', new SpecialChar, 'numeric', 'exists:attr_filed,filed_id'];
                $rules['platform_id']      = ['nullable', 'present', new SpecialChar, 'numeric', 'exists:attr_platform,platform_id'];
                $rules['industry_id']      = ['nullable', 'present', new SpecialChar, 'numeric', 'exists:attr_industry,industry_id'];
                $rules['region_id']        = ['nullable', 'present', new SpecialChar, 'numeric', 'exists:attr_region,region_id'];
                $rules['priceclassify_id'] = ['nullable', 'present', new SpecialChar, 'numeric', 'exists:attr_priceclassify,priceclassify_id'];
                $rules['fansnumlevel_min'] = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['fansnumlevel_max'] = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['readlevel_min']    = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['readlevel_max']    = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['likelevel_min']    = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['likelevel_max']    = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['weekend_status']   = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['included_sataus']  = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['pricelevel_min']   = ['nullable', 'present', new SpecialChar, 'numeric'];
                $rules['pricelevel_max']   = ['nullable', 'present', new SpecialChar, 'numeric'];
                break;

            // 单个商品信息
            case 'oneGoodsInfo':
                $rules['goods_num'] = ['required', new SpecialChar, 'exists:data_goods,goods_num'];
                break;

            // 商品下架
            case 'goodsDown':
                $rules['goods_num'] = ['required', new SpecialChar, 'exists:data_goods,goods_num'];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required'   => '有必填参数未填写',
            'present'    => '参数缺失',
            'active_url' => '非有效链接',
            'numeric'    => '参数需为数字',

            'title.unique' => '商品标题已存在',

            'title.required'       => '商品标题不得为空',
            'title_about.required' => '标题简介不得为空',
            'qq_ID.required'       => 'QQ号不得为空',
            'goods_num.required'   => '商品编号不得为空',
            'modular_id.required'  => '模块不得为空',
            'price_json.required'  => '价格信息不得为空',

            'modular_id.exists'       => '模块不存在',
            'theme_id.exists'         => '主题不存在',
            'filed_id.exists'         => '领域不存在',
            'Platfrom_id.exists'      => '平台不存在',
            'industry_id.exists'      => '行业不存在',
            'region_id.exists'        => '地区不存在',
            'priceclassify_id.exists' => '价格种类不存在',
            'goods_num.exists'        => '商品编号不存在',

            'price_json.json' => '价格信息非JSON格式',

            'qq_ID.numeric'            => 'QQ号需为数字',
            'modular_id.numeric'       => '模块需为数字',
            'theme_id.numeric'         => '主题需为数字',
            'filed_id.numeric'         => '领域需为数字',
            'Platfrom_id.numeric'      => '平台需为数字',
            'industry_id.numeric'      => '行业需为数字',
            'region_id.numeric'        => '地区需为数字',
            'priceclassify_id.numeric' => '价格种类需为数字',
            'fans_num.numeric'         => '粉丝数量需为数字',
        ];
    }
}