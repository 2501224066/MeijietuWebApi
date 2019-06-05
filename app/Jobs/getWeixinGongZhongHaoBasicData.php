<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class getWeixinGongZhongHaoBasicData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $goods_weixin_id; // 微信商品id

    protected $weixin_ID; // 微信名

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($goods_weixin_id, $weixin_ID)
    {
        $this->goods_weixin_id = htmlspecialchars($goods_weixin_id);
        $this->weixin_ID = htmlspecialchars($weixin_ID);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 查询自库数据
        $re = DB::connection('mongodb')
            ->collection('WeiXin_OfficialAccount_Analysis')
            ->where('OfficialAccount_ID', $this->weixin_ID)
            ->first();

        // 存入微信商品表中
        if($re)
            DB::table('goods_weixin')
                ->where('goods_weixin_id', $this->goods_weixin_id)
                ->update([
                    'avg_read_num' => $re['Avg_Read_Num'],
                    'avg_like_num' => $re['Avg_Like_Num'],
                    'avg_comment_num' => $re['Avg_Comment_Num'],
                    'avatar_url' => $re['BasicInfo']['Avatar_Url'],
                    'qrcode_url' => $re['BasicInfo']['Qrcode_Url']
                ]);
    }
}
