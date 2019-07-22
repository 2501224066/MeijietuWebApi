<?php


namespace App\Service;


use App\Models\Indent\IndentInfo;
use App\Models\Nb\Goods;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
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

        if ($input->identity)
            $query->where('identity', $input->identity);

        if ($input->user_num)
            $query->where('user_num', 'like', '%' . $input->user_num . '%');

        if ($input->phone)
            $query->where('user_num', $input->phone);

        if ($input->nickname)
            $query->where('nickname', 'like', '%' . $input->nickname . '%');

        return $query->paginate();

    }

    // 服务商品
    public static function serveGoods($input, $userArr)
    {
        $query = Goods::whereIn('uid', $userArr)
            ->where('satus', Goods::STATUS['上架'])
            ->where('delete_status', Goods::DELETE_STATUS['未删除'])
            ->orderBy('created_at', 'DESC');

        if ($input->user_num)
            $query->where('uid', User::whereUserNum($input->user_num)->value('uid'));

        if ($input->goods_num)
            $query->where('goods_num', $input->goods_num);

        if ($input->verify_status)
            $query->where('verify_status', $input->verify_status);

        return $query->paginate();
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
}