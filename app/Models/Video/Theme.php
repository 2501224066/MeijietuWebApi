<?php


namespace App\Models\Video;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Video\Theme
 *
 * @property int $theme_id 主题名称
 * @property string $theme_name 主题名称
 * @property int $theme_status 主题状态 0=禁用 1=启用
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Video\Fansnumlevel[] $fansnumlevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Video\Filed[] $filed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Video\Platform[] $platform
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Video\Priceclassify[] $priceclassify
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Theme query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Theme whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Theme whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Theme whereThemeStatus($value)
 * @mixin \Eloquent
 */
class Theme extends Model
{
    protected $table = 'video_theme';

    protected $primaryKey = 'theme_id';

    public $guarded = [];

    public $timestamps =false;

    public function filed() : BelongsToMany
    {
        return $this->belongsToMany(Filed::class, "video_theme_filed", 'theme_id', 'filed_id');
    }

    public function fansnumlevel() : BelongsToMany
    {
        return $this->belongsToMany(Fansnumlevel::class, "video_theme_fansnumlevel", 'theme_id', 'fansnumlevel_id');
    }

    public function priceclassify() : BelongsToMany
    {
        return $this->belongsToMany(Priceclassify::class, "video_theme_priceclassify", 'theme_id', 'priceclassify_id');
    }

    public function platform() : BelongsToMany
    {
        return $this->belongsToMany(Platform::class, "video_theme_platform", 'theme_id', 'platform_id');
    }
}