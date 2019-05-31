<?php


namespace App\Models\Weixin;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Weixin\Likelevel
 *
 * @property int $likelevel_id 平均点赞量级id
 * @property string $likelevel_name 平均点赞量级名称
 * @property int $likelevel_min 平均点赞量级最小值
 * @property int $likelevel_max 平均点赞量级最大值
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Likelevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Likelevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Likelevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Likelevel whereLikelevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Likelevel whereLikelevelMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Likelevel whereLikelevelMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Likelevel whereLikelevelName($value)
 * @mixin \Eloquent
 */
class Likelevel extends Model
{
    protected $table = 'weixin_likelevel';

    protected $primaryKey = 'likelevel_id';

    public $guarded = [];

    public $timestamps =false;
}