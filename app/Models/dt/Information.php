<?php


namespace App\Models\dt;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\dt\Information
 *
 * @property int $information_id
 * @property string $title 标题
 * @property string $author 作者
 * @property string $motif_img 装饰图
 * @property string $content 内容
 * @property int $read_num 阅读数
 * @property string $time 创建时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\dt\Information newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\dt\Information newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\dt\Information query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\dt\Information whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\dt\Information whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\dt\Information whereInformationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\dt\Information whereMotifImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\dt\Information whereReadNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\dt\Information whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\dt\Information whereTitle($value)
 * @mixin \Eloquent
 */
class Information extends Model
{
    protected $table = 'dt_information';

    protected $primaryKey = 'information_id';

    public $timestamps = false;

    protected $guarded = [];

    public static function indexPageInformation($co)
    {
        return self::offset(0)->limit($co)->orderBy('time', 'DESC')->get();
    }

    // 增加阅读量
    public static function addReadNum($informationId)
    {
        self::whereInformationId($informationId)->increment('read_num');
    }
}