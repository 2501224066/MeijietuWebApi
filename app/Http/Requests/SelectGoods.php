<?php


namespace App\Http\Requests;


use App\Rules\SpecialChar;

class SelectGoods extends Base
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
            // 搜索微信商品
            case 'selectWeixinGoods':
                $rules['theme_id']         = ['required', new SpecialChar, 'numeric', 'exists:weixin_theme,theme_id'];
                $rules['keyword']          = ['present', new SpecialChar];
                $rules['filed_id']         = ['present', new SpecialChar];
                $rules['fansnumlevel_min'] = ['present', new SpecialChar];
                $rules['fansnumlevel_max'] = ['present', new SpecialChar];
                $rules['priceclassify_id'] = ['present', new SpecialChar];
                $rules['pricelevel_min']   = ['present', new SpecialChar];
                $rules['pricelevel_max']   = ['present', new SpecialChar];
                $rules['readlevel_min']    = ['present', new SpecialChar];
                $rules['readlevel_max']    = ['present', new SpecialChar];
                $rules['region_id']        = ['present', new SpecialChar];
                break;

            // 搜索微博商品
            case 'selectWeiboGoods':
                $rules['theme_id']         = ['required', new SpecialChar, 'numeric', 'exists:weibo_theme,theme_id'];
                $rules['keyword']          = ['present', new SpecialChar];
                $rules['filed_id']         = ['present', new SpecialChar];
                $rules['fansnumlevel_min'] = ['present', new SpecialChar];
                $rules['fansnumlevel_max'] = ['present', new SpecialChar];
                $rules['priceclassify_id'] = ['present', new SpecialChar];
                $rules['pricelevel_min']   = ['present', new SpecialChar];
                $rules['pricelevel_max']   = ['present', new SpecialChar];
                $rules['authtype_id']      = ['present', new SpecialChar];
                $rules['region_id']        = ['present', new SpecialChar];
                break;

            // 搜索视频商品
            case 'selectVideoGoods':
                $rules['theme_id']         = ['required', new SpecialChar, 'numeric', 'exists:video_theme,theme_id'];
                $rules['keyword']          = ['present', new SpecialChar];
                $rules['filed_id']         = ['present', new SpecialChar];
                $rules['platform_id']      = ['present', new SpecialChar];
                $rules['fansnumlevel_min'] = ['present', new SpecialChar];
                $rules['fansnumlevel_max'] = ['present', new SpecialChar];
                $rules['priceclassify_id'] = ['present', new SpecialChar];
                $rules['pricelevel_min']   = ['present', new SpecialChar];
                $rules['pricelevel_max']   = ['present', new SpecialChar];
                $rules['region_id']        = ['present', new SpecialChar];
                break;

            // 搜索自媒体商品
            case 'selectSelfmediaGoods':
                $rules['theme_id']         = ['required', new SpecialChar, 'numeric', 'exists:selfmedia_theme,theme_id'];
                $rules['keyword']          = ['present', new SpecialChar];
                $rules['filed_id']         = ['present', new SpecialChar];
                $rules['platform_id']      = ['present', new SpecialChar];
                $rules['fansnumlevel_min'] = ['present', new SpecialChar];
                $rules['fansnumlevel_max'] = ['present', new SpecialChar];
                $rules['pricelevel_min']   = ['present', new SpecialChar];
                $rules['pricelevel_max']   = ['present', new SpecialChar];
                $rules['region_id']        = ['present', new SpecialChar];
                break;

            // 搜索软文商品
            case 'selectSoftarticleGoods':
                $rules['theme_id']         = ['required', new SpecialChar, 'numeric', 'exists:softarticle_theme,theme_id'];
                $rules['keyword']          = ['present', new SpecialChar];
                $rules['filed_id']         = ['present', new SpecialChar];
                $rules['platform_id']      = ['present', new SpecialChar];
                $rules['sendspeed_id']     = ['present', new SpecialChar];
                $rules['industry_id']      = ['present', new SpecialChar];
                $rules['entryclassify_id'] = ['present', new SpecialChar];
                $rules['pricelevel_min']   = ['present', new SpecialChar];
                $rules['pricelevel_max']   = ['present', new SpecialChar];
                $rules['region_id']        = ['present', new SpecialChar];
                break;

            //单个商品信息
            case 'oneGoodsInfo':
                $rules['goods_id']     = ['required', new SpecialChar, 'numeric'];
                $rules['modular_type'] = ['required', new SpecialChar];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'theme_id . required' => '主题不得为空',
            'theme_id . numeric'  => '主题需为数字',
            'theme_id . exists'   => '主题不存在',

            'goods_id. required'     => '商品不得为空',
            'goods_id . numeric'     => '商品需为数字',
            'modular_type. required' => '模块类型不得为空'
        ];
    }
}