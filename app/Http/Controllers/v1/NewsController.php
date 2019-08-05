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
            ->select('news_id', 'content')
            ->paginate();

        // 该用户消息全部已读
        NewsUser::whereUid($uid)->update(['read_status'=> NewsUser::READ_STATUS['已读']]);

        return $this->success($data);
    }
}