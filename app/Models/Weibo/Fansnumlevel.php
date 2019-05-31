<?php


namespace App\Models\Weibo;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Weibo\Fansnumlevel
 *
 * @property int $fansnumlevel_id 粉丝量级id
 * @property string $fansnumlevel_name 粉丝量级名称
 * @property int $fansnumlevel_min 粉丝量级最小值
 * @property int $fansnumlevel_max 粉丝量级最大值
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Fansnumlevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Fansnumlevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Fansnumlevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Fansnumlevel whereFansnumlevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Fansnumlevel whereFansnumlevelMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Fansnumlevel whereFansnumlevelMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Fansnumlevel whereFansnumlevelName($value)
 * @mixin \Eloquent
 */
class Fansnumlevel extends Model
{
    protected $table = 'weibo_fansnumlevel';

    protected $primaryKey = 'fansnumlevel_id';

    public $guarded = [];

    public $timestamps =false;
}