<?php


namespace App\Http\Controllers\Api;


use App\Models\Nb\Goods;
use Illuminate\Support\Facades\DB;

class TestController extends BaseController
{
    // 商品上架
    public function openGoods()
    {
        Goods::whereVerifyStatus(0)->update([
            'verify_status' => 2,
            'status'        => 1
        ]);

        return $this->success();
    }

    // 微信数据导入
    public function joinWeixinBasicData()
    {
        $data = DB::connection('weixin_mongodb')
            ->collection('WeiXin_OfficialAccount_Analysis')
            ->where('OfficialAccount_ID', '!=' ,'')
            ->get();




    }
}