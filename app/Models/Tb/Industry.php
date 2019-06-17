<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tb\Industry
 *
 * @property int $industry_id 行业id
 * @property string $industry_name 行业名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Industry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Industry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Industry query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Industry whereIndustryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Industry whereIndustryName($value)
 * @mixin \Eloquent
 */
class Industry extends Model
{
    protected $table = "tb_industry";

    protected $primaryKey = 'industry_id';

    public $timestamps = false;

    protected $guarded = [];
}