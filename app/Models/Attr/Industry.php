<?php


namespace App\Models\Attr;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tb\Industry
 *
 * @property int $industry_id 行业id
 * @property string $industry_name 行业名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Industry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Industry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Industry query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Industry whereIndustryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Industry whereIndustryName($value)
 * @mixin \Eloquent
 */
class Industry extends Model
{
    protected $table = "attr_industry";

    protected $primaryKey = 'industry_id';

    public $timestamps = false;

    protected $guarded = [];
}