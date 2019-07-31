<?php


namespace App\Models\Data;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Data\NewsUser
 *
 * @property int $news_id
 * @property int $uid
 * @property int $read_status 阅读状态 0=未阅读 1=已阅读
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\NewsUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\NewsUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\NewsUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\NewsUser whereNewsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\NewsUser whereReadStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\NewsUser whereUid($value)
 * @mixin \Eloquent
 */
class NewsUser extends Model
{
    protected $table = 'data_news_user';

    protected $guarded = [];

    public $timestamps = false;

    const READ_STATUS = [
        '未阅读' => 0,
        '已阅读' => 1
    ];

    /**
     * 用户未阅读消息数量
     * @param string $uid 用户id
     * @return int
     */
    public static function unreadNewsConunt($uid): int
    {
        $count = self::with(['news' => function ($query) {
            $query->where('delete_status', News::DELETE_STATUS['未删除'])
                ->where('release_time', '<=', date('Y-m-d H:i:s'));
        }])
            ->where('uid', $uid)
            ->where('read_status', self::READ_STATUS['未阅读'])
            ->count();

        return $count;
    }
}