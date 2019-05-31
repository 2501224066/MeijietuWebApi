<?php


namespace App\Models\Selfmedia;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Selfmedia\Theme
 *
 * @property int $theme_id 主题id
 * @property string $theme_name 主题名称
 * @property int $theme_status 主题状态 0=禁用 1=启用
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Selfmedia\Fansnumlevel[] $fansnumlevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Selfmedia\Filed[] $filed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Selfmedia\Platform[] $platform
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Theme query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Theme whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Theme whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Theme whereThemeStatus($value)
 * @mixin \Eloquent
 */
class Theme extends Model
{
    protected $table = 'selfmedia_theme';

    protected $primaryKey = 'theme_id';

    public $guarded = [];

    public $timestamps =false;

    public function filed() : BelongsToMany
    {
        return $this->belongsToMany(Filed::class, "selfmedia_theme_filed", 'theme_id', 'filed_id');
    }

    public function fansnumlevel() : BelongsToMany
    {
        return $this->belongsToMany(Fansnumlevel::class, "selfmedia_theme_fansnumlevel", 'theme_id', 'fansnumlevel_id');
    }

    public function platform() : BelongsToMany
    {
        return $this->belongsToMany(Platform::class, "selfmedia_theme_platform", 'theme_id', 'platform_id');
    }
}