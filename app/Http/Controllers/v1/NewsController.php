<?php


namespace App\Http\Controllers\v1;


use App\Models\Data\News;
use App\Models\Data\NewsUser;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\News as NewsRequest;

class NewsController extends BaseController
{
    /**
     * 用户消息
     * @param NewsRequest $request
     * @return mixed
     */
    public function newsBelongSelf(NewsRequest $request)
    {
        $uid = JWTAuth::user()->uid;

        $query = NewsUser::whereUid($uid);
        if ($request->read_status != null)
            $query->where('read_status', $request->read_status);

        $newsIdArr = $query->pluck('news_id');
        $data      = News::whereIn('news_id', $newsIdArr)
            ->where('status', News::STATUS['启用'])
            ->where('release_time', '<=', date('Y-m-d H:i:s'))
            ->orderBy('release_time', 'DESC')
            ->select('content')
            ->paginate();

        return $this->success($data);
    }

    /**
     * 消息内容
     * @param NewsRequest $request
     * @return mixed
     */
    public function newsInfo(NewsRequest $request)
    {
        $new_id = $request->news_id;
        $news   = News::whereNewsId($new_id)->first();

        if ((!$news)
            || ($news->status == News::STATUS['禁用'])
            || ($news->release_time > date('Y-m-d H:i:s')))
            throw new Exception('未找到此条消息');

        return $this->success($news);
    }

    /**
     * 消息已读
     * @param NewsRequest $request
     * @return mixed
     */
    public function newsReaded(NewsRequest $request)
    {
        $news_id_arr = json_decode($request->news_id_json, true);
        foreach ($news_id_arr as $news_id) {
            NewsUser::whereNewsId($news_id)
                ->update([
                    'read_status' => NewsUser::READ_STATUS['已阅读']
                ]);
        }

        return $this->success();
    }

}