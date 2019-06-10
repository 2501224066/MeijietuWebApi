<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Softarticle\Priceclassify
 *
 * @property int $priceclassify_id 价格种类id
 * @property string|null $priceclassify_name 价格种类名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Priceclassify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Priceclassify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Priceclassify query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Priceclassify wherePriceclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Priceclassify wherePriceclassifyName($value)
 * @mixin \Eloquent
 * @property string $tag 标记
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Priceclassify whereTag($value)
 */
class Priceclassify extends Model
{
    protected $table = 'softarticle_priceclassify';

    protected $primaryKey = 'priceclassify_id';

    public $guarded = [];

    public $timestamps =false;
}