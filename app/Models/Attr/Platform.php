<?php


namespace App\Models\Attr;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tb\Platform
 *
 * @property int $platform_id
 * @property string $platform_name
 * @property string|null $logo_path
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Platform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Platform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Platform query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Platform whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Platform wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Platform wherePlatformName($value)
 * @mixin \Eloquent
 */
class Platform extends Model
{
    protected $table = "attr_platform";

    protected $primaryKey = 'platform_id';

    public $timestamps = false;

    protected $guarded = [];
}