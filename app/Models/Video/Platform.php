<?php


namespace App\Models\Video;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Video\Platform
 *
 * @property int $platform_id 平台id
 * @property string $platform_name 平台名称
 * @property string $logo_path 平台logo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Platform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Platform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Platform query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Platform whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Platform wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Platform wherePlatformName($value)
 * @mixin \Eloquent
 */
class Platform extends Model
{
    protected $table = 'video_platform';

    protected $primaryKey = 'platform_id';

    public $guarded = [];

    public $timestamps =false;
}