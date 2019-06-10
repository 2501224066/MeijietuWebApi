<?php


namespace App\Models\Selfmedia;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Selfmedia\Priceclassify
 *
 * @property int $priceclassify_id 价格种类id
 * @property string|null $priceclassify_name 价格种类名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\Priceclassify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\Priceclassify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\Priceclassify query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\Priceclassify wherePriceclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\Priceclassify wherePriceclassifyName($value)
 * @mixin \Eloquent
 * @property string $tag 标记
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmeida\Priceclassify whereTag($value)
 */
class Priceclassify extends Model
{
    protected $table = 'selfmedia_priceclassify';

    protected $primaryKey = 'priceclassify_id';

    public $guarded = [];

    public $timestamps =false;
}