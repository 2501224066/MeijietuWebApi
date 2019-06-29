<?php


namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class IndentSettlement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $goodsId;

    protected $weixin_ID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($goodsId, $weixin_ID)
    {
        $this->goodsId   = $goodsId;
        $this->weixin_ID = $weixin_ID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 查询自库数据
        $re = DB::connection('weixin_mongodb')
            ->collection('WeiXin_OfficialAccount_Analysis')
            ->where('OfficialAccount_ID', $this->weixin_ID)
            ->first();

        // 存入商品表中
        if ($re)
            DB::table('nb_goods')
                ->where('goods_id', $this->goodsId)
                ->update([
                    'avg_read_num'    => $re['Avg_Read_Num'],
                    'avg_like_num'    => $re['Avg_Like_Num'],
                    'avg_comment_num' => $re['Avg_Comment_Num'],
                    'avatar_url'      => $re['BasicInfo']['Avatar_Url'],
                    'qrcode_url'      => $re['BasicInfo']['Qrcode_Url']]);

    }
}