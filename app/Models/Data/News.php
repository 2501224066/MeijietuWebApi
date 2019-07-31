<?php


namespace App\Models\Data;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Data\News
 *
 * @property int $news_id
 * @property string $title 消息标题
 * @property string $content 消息内容
 * @property string $release_time 发布时间
 * @property int $delete_status 删除状态 0=删除 1=删除
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\News query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\News whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\News whereDeleteStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\News whereNewsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\News whereReleaseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\News whereTitle($value)
 * @mixin \Eloquent
 */
class News extends Model
{
    protected $table = 'data_news';

    protected $primaryKey = 'news_id';

    public $timestamps = false;

    protected $guarded = [];

    const DELETE_STATUS = [
        '未删除' => 0,
        '已删除' => 1
    ];

    /**
     * 消息推送
     * @param string $uid 推送对象id
     * @param string $title 标题
     * @param string $content 内容
     */
    public static function put($uid, $title, $content)
    {
        $news_id = News::insertGetId([
            'title'        => $title,
            'content'      => $content,
            'release_time' => date('Y-m-d H:i:s')
        ]);

        if ($news_id && $uid)
            NewsUser::create(['news_id' => $news_id, 'uid' => $uid]);
    }
}