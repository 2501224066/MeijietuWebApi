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

        switch ($this->getFunName()) {
            // 创建微信商品
            case 'createWeixinGoods':
                $rules['goods_title']       = 'required';
                $rules['goods_title_about'] = 'required';
                $rules['fans_num']          = 'required|numeric';
                $rules['weixin_ID']         = 'required|unique:goods_weixin,weixin_ID';
                $rules['theme_id']          = 'required|numeric|exists:weixin_theme,theme_id';
                $rules['filed_id']          = 'required|numeric|exists:weixin_filed,filed_id';
                $rules['region_id']         = 'required|numeric|exists:currency_region,region_id';
                $rules['reserve_status']    = 'required|numeric';
                $rules['qq_ID']             = 'required|numeric';
                $rules['price_data']        = 'required';
                $rules['remarks']           = 'present';
                break;

            // 创建微博商品
            case 'createWeiboGoods':
                $rules['goods_title']       = 'required';
                $rules['goods_title_about'] = 'required';
                $rules['weibo_link']        = 'required|unique:goods_weibo,weibo_link';
                $rules['theme_id']          = 'required|numeric|exists:weibo_theme,theme_id';
                $rules['filed_id']          = 'required|numeric|exists:weibo_filed,filed_id';
                $rules['region_id']         = 'required|numeric|exists:currency_region,region_id';
                $rules['authtype_id']       = 'required|numeric|exists:weibo_authtype,authtype_id';
                $rules['reserve_status']    = 'required|numeric';
                $rules['qq_ID']             = 'required|numeric';
                $rules['price_data']        = 'required';
                $rules['remarks']           = 'present';
                break;

            // 创建视频商品
            case 'createVideoGoods':
                $rules['goods_title']       = 'required';
                $rules['goods_title_about'] = 'required';
                $rules['room_num']          = 'required|unique:goods_video,room_num';
                $rules['fans_num']          = 'required|numeric';
                $rules['theme_id']          = 'required|numeric|exists:video_theme,theme_id';
                $rules['filed_id']          = 'required|numeric|exists:video_filed,filed_id';
                $rules['region_id']         = 'required|numeric|exists:currency_region,region_id';
                $rules['platform_id']       = 'required|numeric|exists:video_platform,platform_id';
                $rules['qq_ID']             = 'required|numeric';
                $rules['price_data']        = 'required';
                $rules['remarks']           = 'present';
                break;

            // 创建自媒体商品
            case 'createSelfmediaGoods':
                $rules['goods_title']       = 'required';
                $rules['goods_title_about'] = 'required';
                $rules['fans_num']          = 'required|numeric';
                $rules['reserve_status']    = 'required|numeric';
                $rules['theme_id']          = 'required|numeric|exists:selfmedia_theme,theme_id';
                $rules['filed_id']          = 'required|numeric|exists:selfmedia_filed,filed_id';
                $rules['region_id']         = 'required|numeric|exists:currency_region,region_id';
                $rules['platform_id']       = 'required|numeric|exists:selfmedia_platform,platform_id';
                $rules['qq_ID']             = 'required|numeric';
                $rules['price_data']        = 'required';
                $rules['remarks']           = 'present';
                break;

            // 创建软文商品
            case 'createSoftarticleGoods':
                $rules['goods_title']       = 'required';
                $rules['goods_title_about'] = 'required';
                $rules['web_link']          = 'required';
                $rules['weekend_send']      = 'required|numeric';
                $rules['news_source']       = 'required|numeric';
                $rules['theme_id']          = 'required|numeric|exists:softarticle_theme,theme_id';
                $rules['filed_id']          = 'required|numeric|exists:softarticle_filed,filed_id';
                $rules['region_id']         = 'required|numeric|exists:currency_region,region_id';
                $rules['platform_id']       = 'required|numeric|exists:softarticle_platform,platform_id';
                $rules['sendspeed_id']      = 'required|numeric|exists:softarticle_sendspeed,sendspeed_id';
                $rules['industry_id']       = 'required|numeric|exists:softarticle_industry,industry_id';
                $rules['entryclassify_id']  = 'required|numeric|exists:softarticle_entryclassify,entryclassify_id';
                $rules['qq_ID']             = 'required|numeric';
                $rules['price_data']        = 'required';
                $rules['remarks']           = 'present';
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'goods_title.required'       => '商品标题不得为空',
            'goods_title_about.required' => '标题简介不得为空',

            'weixin_ID.required' => '微信号不得为空',
            'weixin_ID.unique'   => '微信号已被使用',

            'theme_id.required' => '主题不得为空',
            'theme_id.numeric'  => '主题需为数字',
            'theme_id.exists'   => '主题不存在',

            'filed_id.required' => '领域不得为空',
            'filed_id.numeric'  => '领域需为数字',
            'filed_id.exists'   => '领域不存在',

            'fans_num.required' => '粉丝数量不得为空',
            'fans_num.numeric'  => '粉丝数量需为数字',

            'region_id.required' => '面向地区不得为空',
            'region_id.numeric'  => '面向地区需为数字',
            'region_id.exists'   => '面向地区不存在',

            'qq_ID.remarks' => 'QQ号不得为空',
            'qq_ID.numeric' => 'QQ号需为数字',

            'price_data.required' => '价格信息不得为空',

            'reserve_status.required' => '是否预约不得为空',
            'reserve_status.numeric'  => '是否预约需为数字',

            'weibo_link.required' => '微博链接不得为空',
            'weibo_link.unique'   => '微博链接已被使用',

            'authtype_id.required' => '认证类型不得为空',
            'authtype_id.numeric'  => '认证类型需为数字',
            'authtype_id.exists'   => '认证类型不存在',

            'platform_id.required' => '平台不得为空',
            'platform_id.numeric'  => '平台需为数字',
            'platform_id.exists'   => '平台不存在',

            'room_num.required' => '房间号不得为空',
            'room_num.unique'   => '房间号已被使用',

            'sendspeed_id.required' => '发稿速度不得为空',
            'sendspeed_id.numeric'  => '发稿速度需为数字',
            'sendspeed_id.exists'   => '发稿速度不存在',

            'industry_id.required' => '行业不得为空',
            'industry_id.numeric'  => '行业需为数字',
            'industry_id.exists'   => '行业不存在',

            'web_link.required' => '链接网址不得为空',

            'weekend_send.required' => '周末是否发稿不得为空',
            'weekend_send.numeric'  => '周末是否发稿需为数字',

            'news_source.required' => '新闻源不得为空',
            'news_source.numeric'  => '新闻源需为数字',

            'entryclassify.required'  => '入口种类不得为空',
            'entryclassify.numeric'   => '入口种类需为数字',
            'entryclassify_id.exists' => '入口种类不存在',

        ];
    }
}