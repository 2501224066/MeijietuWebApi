<?php


namespace App\Models\Video;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Video\Fansnumlevel
 *
 * @property int $fansnumlevel_id 粉丝量级id
 * @property string $fansnumlevel_name 粉丝量级名称
 * @property int $fansnumlevel_min 粉丝量级最小值
 * @property int $fansnumlevel_max 粉丝量级最大值
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Fansnumlevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Fansnumlevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Fansnumlevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Fansnumlevel whereFansnumlevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Fansnumlevel whereFansnumlevelMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Fansnumlevel whereFansnumlevelMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Fansnumlevel whereFansnumlevelName($value)
 * @mixin \Eloquent
 */
class Fansnumlevel extends Model
{
    protected $table = 'video_fansnumlevel';

    protected $primaryKey = 'fansnumlevel_id';

    public $guarded = [];

    public $timestamps =false;
}