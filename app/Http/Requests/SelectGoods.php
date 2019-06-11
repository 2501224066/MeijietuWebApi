<?php


namespace App\Http\Requests;


use Illuminate\Http\Request;

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
                $rules['theme_id']         = 'required|numeric|exists:weixin_theme,theme_id';
                $rules['keyword']          = 'present';
                $rules['filed_id']         = 'present';
                $rules['fansnumlevel_min'] = 'present';
                $rules['fansnumlevel_max'] = 'present';
                $rules['priceclassify_id'] = 'present';
                $rules['pricelevel_min']   = 'present';
                $rules['pricelevel_max']   = 'present';
                $rules['readlevel_min']    = 'present';
                $rules['readlevel_max']    = 'present';
                $rules['region_id']        = 'present';
                break;

            // 搜索微博商品
            case 'selectWeiboGoods':
                $rules['theme_id']         = 'required|numeric|exists:weibo_theme,theme_id';
                $rules['keyword']          = 'present';
                $rules['filed_id']         = 'present';
                $rules['fansnumlevel_min'] = 'present';
                $rules['fansnumlevel_max'] = 'present';
                $rules['priceclassify_id'] = 'present';
                $rules['pricelevel_min']   = 'present';
                $rules['pricelevel_max']   = 'present';
                $rules['authtype_id']      = 'present';
                $rules['region_id']        = 'present';
                break;

            // 搜索视频商品
            case 'selectVideoGoods':
                $rules['theme_id']         = 'required|numeric|exists:video_theme,theme_id';
                $rules['keyword']          = 'present';
                $rules['filed_id']         = 'present';
                $rules['platform_id']      = 'present';
                $rules['fansnumlevel_min'] = 'present';
                $rules['fansnumlevel_max'] = 'present';
                $rules['priceclassify_id'] = 'present';
                $rules['pricelevel_min']   = 'present';
                $rules['pricelevel_max']   = 'present';
                $rules['region_id']        = 'present';
                break;

            // 搜索自媒体商品
            case 'selectSelfmediaGoods':
                $rules['theme_id']         = 'required|numeric|exists:selfmedia_theme,theme_id';
                $rules['keyword']          = 'present';
                $rules['filed_id']         = 'present';
                $rules['platform_id']      = 'present';
                $rules['fansnumlevel_min'] = 'present';
                $rules['fansnumlevel_max'] = 'present';
                $rules['pricelevel_min']   = 'present';
                $rules['pricelevel_max']   = 'present';
                $rules['region_id']        = 'present';
                break;

            // 搜索软文商品
            case 'selectSoftarticleGoods':
                $rules['theme_id']         = 'required|numeric|exists:softarticle_theme,theme_id';
                $rules['keyword']          = 'present';
                $rules['filed_id']         = 'present';
                $rules['platform_id']      = 'present';
                $rules['sendspeed_id']     = 'present';
                $rules['industry_id']      = 'present';
                $rules['entryclassify_id'] = 'present';
                $rules['pricelevel_min']   = 'present';
                $rules['pricelevel_max']   = 'present';
                $rules['region_id']        = 'present';
                break;

            //单个商品信息
            case 'oneGoodsInfo':
                $rules['goods_id']     = 'required|numeric';
                $rules['modular_type'] = 'required';
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