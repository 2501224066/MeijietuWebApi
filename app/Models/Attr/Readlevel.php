<?php


namespace App\Models\Attr;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tb\Readlevel
 *
 * @property int $readlevel_id 阅读量级id
 * @property string $readlevel_name 平均阅读量级名称
 * @property int $readlevel_min 平均阅读量级最小值
 * @property int $readlevel_max 平均阅读量级最大值
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Readlevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Readlevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Readlevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Readlevel whereReadlevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Readlevel whereReadlevelMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Readlevel whereReadlevelMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Readlevel whereReadlevelName($value)
 * @mixin \Eloquent
 */
class Readlevel extends Model
{
    protected $table = "attr_readlevel";

    protected $primaryKey = 'readlevel_id';

    public $timestamps = false;

    protected $guarded = [];
}