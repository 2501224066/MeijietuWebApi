<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Softarticle\Industry
 *
 * @property int $industry_id 行业id
 * @property string $industry_name 行业名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Industry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Industry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Industry query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Industry whereIndustryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Industry whereIndustryName($value)
 * @mixin \Eloquent
 */
class Industry extends Model
{
    protected $table = 'softarticle_industry';

    protected $primaryKey = 'industry_id';

    public $guarded = [];

    public $timestamps =false;
}