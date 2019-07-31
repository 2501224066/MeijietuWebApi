<?php


namespace App\Models\Attr;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tb\Fansnumlevel
 *
 * @property int $fansnumlevel_id 粉丝量级id
 * @property string $fansnumlevel_name 粉丝量级名称
 * @property int $fansnumlevel_min 粉丝量级最小值
 * @property int $fansnumlevel_max 粉丝量级最大值
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Fansnumlevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Fansnumlevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Fansnumlevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Fansnumlevel whereFansnumlevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Fansnumlevel whereFansnumlevelMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Fansnumlevel whereFansnumlevelMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Fansnumlevel whereFansnumlevelName($value)
 * @mixin \Eloquent
 */
class Fansnumlevel extends Model
{
    protected $table = "attr_fansnumlevel";

    protected $primaryKey = 'fansnumlevel_id';

    public $timestamps = false;

    protected $guarded = [];
}