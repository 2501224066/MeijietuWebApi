<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Softarticle\Platform
 *
 * @property int $platform_id 平台id
 * @property string $platform_name 平台名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Platform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Platform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Platform query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Platform wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Platform wherePlatformName($value)
 * @mixin \Eloquent
 */
class Platform extends Model
{
    protected $table = 'softarticle_platform';

    protected $primaryKey = 'platform_id';

    public $guarded = [];

    public $timestamps =false;
}