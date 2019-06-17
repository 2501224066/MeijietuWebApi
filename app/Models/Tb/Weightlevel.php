<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tb\Weightlevel
 *
 * @property int $weightlevel_id 权重等级id
 * @property string $weightlevel_name 权重等级名称
 * @property string $img_path 权重等级图片
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Weightlevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Weightlevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Weightlevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Weightlevel whereImgPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Weightlevel whereWeightlevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Weightlevel whereWeightlevelName($value)
 * @mixin \Eloquent
 */
class Weightlevel extends Model
{
    protected $table = "tb_weightlevel";

    protected $primaryKey = 'weightlevel_id';

    public $timestamps = false;

    protected $guarded = [];
}