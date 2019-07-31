<?php


namespace App\Models\Attr;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tb\Pricelevel
 *
 * @property int $pricelevel_id 价格量级id
 * @property string $pricelevel_name 价格量级名称
 * @property int $pricelevel_min 价格量级最小值
 * @property int $pricelevel_max 价格量级最大值
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Pricelevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Pricelevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Pricelevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Pricelevel wherePricelevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Pricelevel wherePricelevelMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Pricelevel wherePricelevelMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Pricelevel wherePricelevelName($value)
 * @mixin \Eloquent
 */
class Pricelevel extends Model
{
    protected $table = "attr_pricelevel";

    protected $primaryKey = 'pricelevel_id';

    public $timestamps = false;

    protected $guarded = [];
}