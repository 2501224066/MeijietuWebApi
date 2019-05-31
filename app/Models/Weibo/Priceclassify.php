<?php


namespace App\Models\Weibo;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Weibo\Priceclassify
 *
 * @property int $priceclassify_id 价格种类id
 * @property string $priceclassify_name 价格种类名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Priceclassify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Priceclassify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Priceclassify query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Priceclassify wherePriceclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Priceclassify wherePriceclassifyName($value)
 * @mixin \Eloquent
 */
class Priceclassify extends Model
{
    protected $table = 'weibo_priceclassify';

    protected $primaryKey = 'priceclassify_id';

    public $guarded = [];

    public $timestamps =false;
}