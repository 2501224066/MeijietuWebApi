<?php


namespace App\Models\Weixin;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Weixin\Priceclassify
 *
 * @property int $priceclassify_id 价格种类id
 * @property string $priceclassify_name 价格种类名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Priceclassify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Priceclassify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Priceclassify query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Priceclassify wherePriceclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Priceclassify wherePriceclassifyName($value)
 * @mixin \Eloquent
 */
class Priceclassify extends Model
{
    protected $table = 'weixin_priceclassify';

    protected $primaryKey = 'priceclassify_id';

    public $guarded = [];

    public $timestamps =false;
}