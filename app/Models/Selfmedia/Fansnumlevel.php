<?php


namespace App\Models\Selfmedia;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Selfmedia\Fansnumlevel
 *
 * @property int $fansnumlevel_id 粉丝量级id
 * @property string $fansnumlevel_name 粉丝量级名称
 * @property int $fansnumlevel_min 粉丝量级最小值
 * @property int $fansnumlevel_max 粉丝量级最大值
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Fansnumlevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Fansnumlevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Fansnumlevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Fansnumlevel whereFansnumlevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Fansnumlevel whereFansnumlevelMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Fansnumlevel whereFansnumlevelMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Fansnumlevel whereFansnumlevelName($value)
 * @mixin \Eloquent
 */
class Fansnumlevel extends Model
{
    protected $table = 'selfmedia_fansnumlevel';

    protected $primaryKey = 'fansnumlevel_id';

    public $guarded = [];

    public $timestamps =false;
}