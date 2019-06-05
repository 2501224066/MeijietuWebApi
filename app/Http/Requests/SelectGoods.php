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

        switch ($this->getFunName())
        {
            // 搜索微信商品
            case 'selectWeixinGoods':
                $rules['theme_id'] = 'required|numeric|exists:weixin_theme,theme_id';
                $rules['keyword'] = 'present';
                $rules['filed_id'] = 'present';
                $rules['fansnumlevel_min'] = 'present';
                $rules['fansnumlevel_max'] = 'present';
                $rules['priceclassify_id'] = 'required|numeric|exists:weixin_priceclassify,priceclassify_id';
                $rules['pricelevel_min'] = 'present';
                $rules['pricelevel_max'] = 'present';
                $rules['readlevel_min'] = 'present';
                $rules['readlevel_max'] = 'present';
                $rules['region_id'] = 'present';
                break;

            // 搜索微博商品
            case 'selectWeiboGoods':
                $rules['theme_id'] = 'required|numeric|exists:weibo_theme,theme_id';
                $rules['keyword'] = 'present';
                $rules['filed_id'] = 'present';
                $rules['fansnumlevel_min'] = 'present';
                $rules['fansnumlevel_max'] = 'present';
                $rules['priceclassify_id'] = 'required|numeric|exists:weibo_priceclassify,priceclassify_id';
                $rules['pricelevel_min'] = 'present';
                $rules['pricelevel_max'] = 'present';
                $rules['authtype_id'] = 'present';
                $rules['region_id'] = 'present';
                break;

            // 搜索视频商品
            case 'selectVidoeGoods':
                $rules['theme_id'] = 'required|numeric|exists:weibo_theme,theme_id';
                $rules['keyword'] = 'present';
                $rules['filed_id'] = 'present';
                $rules['platform_id'] = 'present';
                $rules['fansnumlevel_min'] = 'present';
                $rules['fansnumlevel_max'] = 'present';
                $rules['priceclassify_id'] = 'required|numeric|exists:video_priceclassify,priceclassify_id';
                $rules['pricelevel_min'] = 'present';
                $rules['pricelevel_max'] = 'present';
                $rules['region_id'] = 'present';
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return  [
            'theme_id.required' => '主题不得为空',
            'theme_id.numeric' => '主题需为数字',
            'theme_id.exists' => '主题不存在',

            'priceclassify_id.required' => '价格种类不得为空',
            'priceclassify_id.numeric' => '价格种类需为数字',
            'priceclassify_id.exists' => '价格种类不存在',
        ];
    }
}