<?php


namespace App\Models\Dt;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Dt\Information
 *
 * @property int $information_id
 * @property string $title 标题
 * @property string $author 作者
 * @property string $motif_img 装饰图
 * @property string $content 内容
 * @property int $read_num 阅读数
 * @property string $time 创建时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Information newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Information newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Information query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Information whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Information whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Information whereInformationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Information whereMotifImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Information whereReadNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Information whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Information whereTitle($value)
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

    /**
     * 增加阅读量
     * @param int $informationId 资讯id
     */
    public static function addReadNum($informationId)
    {
        self::whereInformationId($informationId)->increment('read_num');
    }
}