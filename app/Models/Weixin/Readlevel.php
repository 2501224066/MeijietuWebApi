<?php


namespace App\Models\Weixin;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Weixin\Readlevel
 *
 * @property int $readlevel_id 阅读量级id
 * @property string $readlevel_name 平均阅读量级名称
 * @property int $readlevel_min 平均阅读量级最小值
 * @property int $readlevel_max 平均阅读量级最大值
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Readlevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Readlevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Readlevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Readlevel whereReadlevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Readlevel whereReadlevelMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Readlevel whereReadlevelMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Readlevel whereReadlevelName($value)
 * @mixin \Eloquent
 */
class Readlevel extends Model
{
    protected $table = 'weixin_readlevel';

    protected $primaryKey = 'readlevel_id';

    public $guarded = [];

    public $timestamps =false;
}