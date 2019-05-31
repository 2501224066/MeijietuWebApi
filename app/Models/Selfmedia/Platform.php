<?php


namespace App\Models\Selfmedia;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Selfmedia\Platform
 *
 * @property int $platform_id 平台id
 * @property string $platform_name 平台名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Platform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Platform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Platform query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Platform wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Platform wherePlatformName($value)
 * @mixin \Eloquent
 */
class Platform extends Model
{
    protected $table = 'selfmedia_platform';

    protected $primaryKey = 'platform_id';

    public $guarded = [];

    public $timestamps =false;
}