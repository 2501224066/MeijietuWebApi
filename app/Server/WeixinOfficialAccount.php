<?php


namespace App\Server;

use Illuminate\Support\Facades\DB;

class WeixinOfficialAccount
{
    public static function articleData($title)
    {
        // 查出该公众号Biz
        $dt = DB::connection('weixin_mongodb')
            ->collection('WeiXin_OfficialAccount_Analysis')
            ->where('OfficialAccount_Name', $title)
            ->first();

        $article = null;
        if ($dt && $dt->Biz) {
            // 根据Biz拿到文章
            $article = DB::connection('weixin_mongodb')
                ->collection('WeiXin_OfficialAccount_Post')
                ->where('Biz', $dt->Biz)
                ->limit(8)
                ->orderBy('PostDateTime', 'DESC')
                ->get()
                ->each(function ($item) {
                    // 拼出文章链接
                    $item->article_link = "https://mp.weixin.qq.com/s?__biz=".$item->Biz."&mid=".$item->Mid."&idx=".$item->Idx."&sn=".$item->Sn;

                    // 查询文章阅读...
                    $articleDtCount = DB::connection('weixin_mongodb')
                        ->collection('WeiXin_OfficialAccount_Post')
                        ->where('Biz', $item->Biz)
                        ->first();

                    // 加入文章阅读数...
                    $item->readNum = empty($articleDtCount->Read_Num) ? 0 : $articleDtCount->Read_Num;
                    $item->likeNum = empty($articleDtCount->Like_Num) ? 0 : $articleDtCount->Like_Num;
                });
        }

        return $article;
    }
}