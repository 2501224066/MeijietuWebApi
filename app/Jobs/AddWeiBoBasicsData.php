<?php


namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AddWeiBoBasicsData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $goodsId;

    protected $link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($goodsId, $link)
    {
        $this->goodsId = $goodsId;
        $this->link    = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 截取链接最后数组ID
        if (strpos($this->link, '?')) {
            $arr = explode('/', substr($this->link, 0, strpos($this->link, '?')));
        } else {
            $arr = explode('/', $this->link);
        }
        $id = end($arr);

        // 查询自库数据
        $re = DB::connection('weibo_mongodb')
            ->collection('WeiBo_Analysis')
            ->where('WeiBo_Uid', $id)
            ->first();

        // 存入商品表中
        if ($re)
            DB::table('Goods')
                ->where('goods_id', $this->goodsId)
                ->update([
                    'avg_like_num'    => $re['Avg_Like_Num_Last10'],
                    'avg_comment_num' => $re['Avg_Comment_Num_Last10'],
                    'avg_retweet_num' => $re['Avg_Retweet_Num_Last10'],
                    'avatar_url'      => $re['BasicInfo']['Avatar_Url'],
                    'fans_num'        => $re['BasicInfo']['Fans_Num']
                ]);

    }
}