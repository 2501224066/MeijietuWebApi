<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tb\Platform
 *
 * @property int $platform_id
 * @property string $platform_name
 * @property string|null $logo_path
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Platform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Platform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Platform query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Platform whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Platform wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Platform wherePlatformName($value)
 * @mixin \Eloquent
 */
class Platform extends Model
{
    protected $table = "tb_platform";

    protected $primaryKey = 'platform_id';

    public $timestamps = false;

    protected $guarded = [];
}