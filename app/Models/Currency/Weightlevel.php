<?php


namespace App\Models\Currency;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Currency\Weightlevel
 *
 * @property int $weightlevel_id 权重等级id
 * @property string $weightlevel_name 权重等级名称
 * @property string $img_path 权重等级图片
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency\Weightlevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency\Weightlevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency\Weightlevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency\Weightlevel whereImgPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency\Weightlevel whereWeightlevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency\Weightlevel whereWeightlevelName($value)
 * @mixin \Eloquent
 */
class Weightlevel extends Model
{
    protected $table = 'currency_weightlevel';

    protected $primaryKey = 'weightlevel_id';

    public $guarded = [];

    public $timestamps =false;
}