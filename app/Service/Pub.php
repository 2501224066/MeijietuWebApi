<?php


namespace App\Service;


use App\Models\Nb\Goods;
use App\Models\Nb\GoodsPrice;
use Mockery\Exception;

class Pub
{
    const CODE_TYPE = [
        '检查手机号'  => 'checkPhone',
        '下一步令牌'  => 'nextToken',
        '动态登录'   => 'codeSignIn',
        '重置密码'   => 'resetPassCode',
        '个人实名认证' => 'realnamePeople',
        '企业实名认证' => 'realnameEnterprise',
        '修改手机号'  => 'savePhone',
        '修改密码'   => 'savePass'
    ];

    const UPLOAD_TYPE = [
        '身份证'  => 'ID_card',
        '营业执照' => 'business_license',
        '头像'   => 'head_portrait',
        '订单文档' => 'indent_word'
    ];

    // 检查状态
    public static function checkStatus($status, $needStatus)
    {
        if ($status != $needStatus)
            throw new Exception('状态非法');

        return true;
    }

    /*
     *  消除制造商品
     *  初始创造的一批商品,当用户录入商品后，判断再初始商品中是否有重复的，有则删除初始商品
     */
    public static function delZZGoods($goodsId)
    {
        $goods = Goods::whereGoodsId($goodsId)->first();
        $arr =  Goods::whereUid(0)
            ->where('weixin_ID', $goods->weixin_ID)
            ->orWhere('link', $goods->link)
            ->pluck('goods_id');

        foreach ($arr as $goods_id){
            Goods::whereGoodsId($goods_id)->delete();
            GoodsPrice::whereGoodsId($goods_id)->delete();
        }
    }
}