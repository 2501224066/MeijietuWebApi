<?php


namespace App\Models\Video;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Video\Priceclassify
 *
 * @property int $priceclassify_id 价格种类id
 * @property string|null $priceclassify_name 价格种类名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Priceclassify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Priceclassify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Priceclassify query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Priceclassify wherePriceclassifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Priceclassify wherePriceclassifyName($value)
 * @mixin \Eloquent
 * @property string $tag 标记
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Priceclassify whereTag($value)
 */
class Priceclassify extends Model
{
    protected $table = 'video_priceclassify';

    protected $primaryKey = 'priceclassify_id';

    public $guarded = [];

    public $timestamps =false;
}