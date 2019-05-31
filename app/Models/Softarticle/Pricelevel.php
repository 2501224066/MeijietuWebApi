<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Softarticle\Pricelevel
 *
 * @property int $pricelevel_id 价格量级id
 * @property string $pricelevel_name 价格量级名称
 * @property int $pricelevel_min 价格量级最小值
 * @property int $pricelevel_max 价格量级最大值
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Pricelevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Pricelevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Pricelevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Pricelevel wherePricelevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Pricelevel wherePricelevelMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Pricelevel wherePricelevelMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Pricelevel wherePricelevelName($value)
 * @mixin \Eloquent
 */
class Pricelevel extends Model
{
    protected $table = 'softarticle_pricelevel';

    protected $primaryKey = 'pricelevel_id';

    public $guarded = [];

    public $timestamps =false;
}