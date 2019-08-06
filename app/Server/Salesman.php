<?php


namespace App\Server;


use App\Models\Data\IndentInfo;
use App\Models\Data\Goods;
use App\Models\Data\GoodsPrice;
use App\Models\Attr\Modular;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class Salesman
{
    // 分配客服
    public static function withSalesman($uid)
    {
        // 必须没有客服
        self::checkUserHasSalesman($uid, 'n');
        // 取得一个客服id
        $salesman_id = self::getSalesman();
        // 将客服id与用户id存入它们的关联表
        $re = User::whereUid($uid)->update([
            'salesman_id'   => $salesman_id,
            'salesman_name' => User::whereUid($salesman_id)->value('nickname')
        ]);
        if (!$re)
            throw new Exception('分配客服失败');

        return true;
    }

    // 检查客服
    public static function checkUserHasSalesman($uid, $io)
    {
        $re = User::whereUid($uid)->value('salesman_id');
        switch ($io) {
            case 'n':
                if ($re) throw new Exception('已有专属客服');
                break;

            case 'y' :
                if (!$re) throw new Exception('没有客服，请到个人中心设置客服');
                break;
        }

        return true;
    }

    // 循环获取客服id
    public static function getSalesman()
    {
        // 所有客服id
        $salesmanArr = User::whereIdentity(User::IDENTIDY['业务员'])
            ->where('status', User::STATUS['启用'])
            ->pluck('uid');

        // 位置初始值
        $init = 0;

        // 位置不存在设置初始值
        if (!Cache::has('salesman_tag')) {
            Cache::put('salesman_tag', $init, 60);
            return $salesmanArr[$init];
        }

        // 位置超出回归初始值
        if (empty($salesmanArr[Cache::get('salesman_tag') + 1])) {
            Cache::put('salesman_tag', $init, 60);
            return $salesmanArr[$init];
        }

        Cache::put('salesman_tag', Cache::get('salesman_tag') + 1, 60);

        return $salesmanArr[Cache::get('salesman_tag') + 1];
    }

    // 获取客服信息
    public static function salesmanInfo()
    {
        $salesman_id = JWTAuth::user()->salesman_id;
        if (!$salesman_id)
            throw new Exception('未找到客服信息');

        $info = User::whereUid($salesman_id)->first();
        return [
            'salesman_id'            => $info->uid,
            'salesman_qq_ID'         => $info->qq_ID,
            'salesman_weixin_ID'     => $info->weixin_ID,
            'salesman_name'          => $info->nickname,
            'salesman_head_portrait' => $info->head_portrait
        ];
    }

    // 服务用户
    public static function serveUser($input)
    {
        $query = User::whereSalesmanId(JWTAuth::user()->uid)
            ->where('status', User::STATUS['启用'])
            ->with('wallet:uid,available_money,status,remark')
            ->select(['uid', 'user_num', 'phone', 'nickname', 'sex', 'created_at', 'identity', 'realname_status', 'status']);

        if ($input->identity != null)
            $query->where('identity', $input->identity);

        if ($input->user_num != null)
            $query->where('user_num', 'like', '%' . $input->user_num . '%');

        if ($input->phone != null)
            $query->where('user_num', $input->phone);

        if ($input->nickname != null)
            $query->where('nickname', 'like', '%' . $input->nickname . '%');

        return $query->paginate();

    }

    // 服务商品
    public static function serveGoods($input, $userArr)
    {
        $query = Goods::whereIn('uid', $userArr)
            ->with('goods_price')
            ->where('delete_status', Goods::DELETE_STATUS['未删除'])
            ->orderBy('created_at', 'DESC');

        if ($input->user_num != null)
            $query->where('uid', User::whereUserNum($input->user_num)->value('uid'));

        if ($input->goods_num != null)
            $query->where('goods_num', $input->goods_num);

        if ($input->verify_status != null)
            $query->where('verify_status', $input->verify_status);

        $data = $query->paginate();

        foreach ($data->items() as &$item) {
            $user                = User::whereUid($item->uid)->first();
            $item->user_nickname = $user->nickname;
            $item->user_phone    = $user->phone;
            $item->user_num      = $user->user_num;
        }

        return $data;
    }

    // 服务订单
    public static function serveIndent($input)
    {
        $query = IndentInfo::whereSalesmanId(JWTAuth::user()->uid)
            ->where('delete_status', IndentInfo::DELETE_STATUS['未删除'])
            ->orderBy('create_time', 'DESC');

        if ($input->buyer_num)
            $query->where('buyer_id', User::whereUserNum($input->buyer_num)->value('uid'));

        if ($input->seller_num)
            $query->where('seller_id', User::whereUserNum($input->seller_num)->value('uid'));

        if ($input->indent_num)
            $query->where('indent_num', $input->indent_num);

        if ($input->bargaining_status)
            $query->where('bargaining_status', $input->bargaining_status);

        $data = $query->paginate();

        foreach ($data->items() as &$item) {
            $buyer              = User::whereUid($item->buyer_id)->first();
            $item->buyer_name   = $buyer->nickname;
            $item->buyer_phone  = $buyer->phone;
            $item->buyer_num    = $buyer->user_num;
            $seller             = User::whereUid($item->seller_id)->first();
            $item->seller_name  = $seller->nickname;
            $item->seller_phone = $seller->phone;
            $item->seller_num   = $buyer->user_num;
        }

        return $data;
    }

    // 通过审核
    public static function verifySuccess($goodsNum)
    {
        $goods = Goods::whereGoodsNum($goodsNum)->first();
        if ($goods->verify_status != Goods::VERIFY_STATUS['待审核'])
            throw new Exception('审核状态非法');

        // 软文商品必须已经设置价格
        if ((Modular::whereModularId($goods->modular_id)->value('tag') == Modular::TAG['软文营销'])
            && (GoodsPrice::whereGoodsId($goods->goods_id)->value('price') * 1 == 0))
            throw new Exception('软文商品必须优先设置价格');

        $goods->verify_cause  = "";
        $goods->verify_status = Goods::VERIFY_STATUS['已通过'];
        $goods->status        = Goods::STATUS['上架'];
        $re                   = $goods->save();
        if (!$re)
            throw new Exception('操作失败');

        Log::info('【商品】 '.$goodsNum.'审核通过');
    }

    // 未通过审核
    public static function verifyFail($goodsNum, $verifyCause)
    {
        $goods = Goods::whereGoodsNum($goodsNum)->first();
        if ($goods->verify_status != Goods::VERIFY_STATUS['待审核'])
            throw new Exception('审核状态非法');

        $goods->verify_status = Goods::VERIFY_STATUS['未通过'];
        $goods->verify_cause  = $verifyCause;
        $re                   = $goods->save();
        if (!$re)
            throw new Exception('操作失败');

        Log::info('【商品】 '.$goodsNum.'审核不通过');
    }

    // 议价操作
    public static function bargainingOP($indentNum, $sellerIncome)
    {
        $indent = IndentInfo::whereIndentNum($indentNum)->first();
        if ($sellerIncome > $indent->seller_income)
            throw new Exception('议价卖家收入不得高于原始卖家收入');

        $indent->bargaining_status = IndentInfo::BARGAINING_STATUS['已完成'];
        $indent->bargaining_reduce = $indent->seller_income - $sellerIncome;
        $indent->seller_income     = $sellerIncome;
        $re                        = $indent->save();
        if (!$re)
            throw new Exception('操作失败');

        Log::info('【订单】 '.$indentNum.'议价完成');
    }

    // 设置软文价格操作
    public static function setSoftArticlePriceOP($goodsNum, $price)
    {
        $goods = Goods::whereGoodsNum($goodsNum)->first();

        Pub::checkParm($goods->verify_status, Goods::VERIFY_STATUS['待审核'], '商品状态非法');

        if ((Modular::whereModularId($goods->modular_id)->value('tag') != Modular::TAG['软文营销'])
            || (GoodsPrice::whereGoodsId($goods->goods_id)->value('price') * 1 != 0)
            || ($price <= 0))
            throw new Exception('非法操作');

        $re = GoodsPrice::whereGoodsId($goods->goods_id)->update(['price' => $price]);
        if (!$re)
            throw new Exception('操作失败');

        Log::info('【商品】 '.$goodsNum.'设置软文价格完成');
    }
}