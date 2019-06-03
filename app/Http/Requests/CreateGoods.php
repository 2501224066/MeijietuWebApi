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
                $rules['weixin_ID'] = 'required|unique:goods_weixin,weixin_ID';
                $rules['filed_id'] = 'required|numeric';
                $rules['fans_num'] = 'required|numeric';
                $rules['region_id'] = 'required|numeric';
                $rules['reserve_status'] = 'required|numeric';
                $rules['remarks'] = 'present';
                $rules['qq_ID'] = 'required|numeric';
                $rules['price_data'] = 'required';
                break;

            // 创建微博商品
            case 'createWeiboGoods':
                $rules['theme_id'] = 'required|numeric';
                $rules['goods_title'] = 'required';
                $rules['goods_title_about'] = 'required';
                $rules['weibo_link'] = 'required|unique:goods_weibo,weibo_link';
                $rules['filed_id'] = 'required|numeric';
                $rules['region_id'] = 'required|numeric';
                $rules['reserve_status'] = 'required|numeric';
                $rules['remarks'] = 'present';
                $rules['qq_ID'] = 'required|numeric';
                $rules['price_data'] = 'required';
                break;

            // 创建视频商品
            case 'createVideoGoods':
                $rules['theme_id'] = 'required|numeric';
                $rules['goods_title'] = 'required';
                $rules['goods_title_about'] = 'required';
                $rules['room_num'] = 'required|unique:goods_video,room_num';
                $rules['fans_num'] = 'required|numeric';
                $rules['platform_id'] = 'required|numeric';
                $rules['filed_id'] = 'required|numeric';
                $rules['region_id'] = 'required|numeric';
                $rules['remarks'] = 'present';
                $rules['qq_ID'] = 'required|numeric';
                $rules['price_data'] = 'required';
                break;

            // 创建自媒体商品
            case 'createSelfmediaGoods':
                $rules['theme_id'] = 'required|numeric';
                $rules['goods_title'] = 'required';
                $rules['goods_title_about'] = 'required';
                $rules['reserve_status'] = 'required|numeric';
                $rules['platform_id'] = 'required|numeric';
                $rules['filed_id'] = 'required|numeric';
                $rules['region_id'] = 'required|numeric';
                $rules['remarks'] = 'present';
                $rules['qq_ID'] = 'required|numeric';
                $rules['price'] = 'required';
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
            'weixin_ID.unique' => '微信号已被使用',
            'filed_id.required' => '领域不得为空',
            'fans_num.required' => '粉丝数量不得为空',
            'region_id.required' => '面向地区不得为空',
            'qq_ID.remarks' => 'QQ号不得为空',
            'price_data.required' => '价格信息不得为空',
            'reserve_status.required' => '是否预约不得为空',
            'theme_id.numeric' => '主题需为数字',
            'filed_id.numeric' => '领域需为数字',
            'qq_ID.numeric' => 'QQ号需为数字',
            'region_id.numeric' => '面向地区需为数字',
            'reserve_status.numeric' => '是否预约需为数字',

            'weibo_link.required' => '微博链接不得为空',
            'weibo_link.unique' => '微博链接已被使用',

            'platform_id.required' => '平台不得为空',
            'platform_id.numeric' => '平台需为数字',
            'room_num.required' => '房间号不得为空',
            'room_num.unique' => '房间号已被使用',

            'price.required' => '价格不得为空',

        ];
    }
}