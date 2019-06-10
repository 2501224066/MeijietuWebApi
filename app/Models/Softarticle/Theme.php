<?php


namespace App\Models\Softarticle;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Softarticle\Theme
 *
 * @property int $theme_id 主题id
 * @property string $theme_name 主题名称
 * @property int $theme_status 主题状态 0=禁用 1=启用
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Softarticle\Entryclassify[] $entryclassify
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Softarticle\Filed[] $filed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Softarticle\Industry[] $industry
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Softarticle\Platform[] $platform
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Softarticle\Pricelevel[] $pricelevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Softarticle\Sendspeed[] $sendspeed
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Theme query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Theme whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Theme whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Theme whereThemeStatus($value)
 * @mixin \Eloquent
 */
class Theme extends Model
{
    protected $table = 'softarticle_theme';

    protected $primaryKey = 'theme_id';

    public $guarded = [];

    public $timestamps =false;

    public function filed() : BelongsToMany
    {
        return $this->belongsToMany(Filed::class, "softarticle_theme_filed", 'theme_id', 'filed_id');
    }

    public function pricelevel() : BelongsToMany
    {
        return $this->belongsToMany(Pricelevel::class, "softarticle_theme_pricelevel", 'theme_id', 'pricelevel_id');
    }

    public function platform() : BelongsToMany
    {
        return $this->belongsToMany(Platform::class, "softarticle_theme_platform", 'theme_id', 'platform_id');
    }

    public function entryclassify() : BelongsToMany
    {
        return $this->belongsToMany(Entryclassify::class, "softarticle_theme_entryclassify", 'theme_id', 'entryclassify_id');
    }

    public function industry() : BelongsToMany
    {
        return $this->belongsToMany(Industry::class, "softarticle_theme_industry", 'theme_id', 'industry_id');
    }

    public function sendspeed() : BelongsToMany
    {
        return $this->belongsToMany(Sendspeed::class, "softarticle_theme_sendspeed", 'theme_id', 'sendspeed_id');
    }

    public function priceclassify() : BelongsToMany
    {
        return $this->belongsToMany(Priceclassify::class, "softarticle_theme_priceclassify", 'theme_id', 'priceclassify_id');
    }
}