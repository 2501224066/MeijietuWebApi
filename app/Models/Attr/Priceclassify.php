<?php


namespace App\Models\Attr;


use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Tb\Priceclassify
 *
 * @property int $priceclassify_id 价格种类id
 * @property string $priceclassify_name 价格种类名称
 * @property string $tag
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Priceclassify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Priceclassify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Priceclassify query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Priceclassify wherePriceclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Priceclassify wherePriceclassifyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Priceclassify whereTag($value)
 * @mixin \Eloquent
 */
class Priceclassify extends Model
{
    protected $table = "attr_priceclassify";

    protected $primaryKey = 'priceclassify_id';

    public $timestamps = false;

    protected $guarded = [];
}