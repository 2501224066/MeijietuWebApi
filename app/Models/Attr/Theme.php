<?php


namespace App\Models\Attr;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Tb\Theme
 *
 * @property int $theme_id
 * @property string $theme_name
 * @property string $theme_status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attr\Fansnumlevel[] $fansnumlevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attr\Filed[] $filed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attr\Industry[] $industry
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attr\Likelevel[] $likelevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attr\Platform[] $platform
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attr\Priceclassify[] $priceclassify
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attr\Pricelevel[] $pricelevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attr\Readlevel[] $readlevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attr\Region[] $region
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attr\Weightlevel[] $weightlevel
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Theme query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Theme whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Theme whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Theme whereThemeStatus($value)
 * @mixin \Eloquent
 */
class Theme extends Model
{
    protected $table = 'attr_theme';

    protected $primaryKey = 'theme_id';

    protected $guarded = [];

    public $timestamps = false;

    // 领域
    public function filed() : BelongsToMany
    {
        return $this->belongsToMany(Filed::class, "attr_theme_filed", 'theme_id', 'filed_id');
    }

    // 平台
    public function platform() : BelongsToMany
    {
        return $this->belongsToMany(Platform::class, "attr_theme_platform", 'theme_id', 'platform_id');
    }

    // 行业
    public function industry() : BelongsToMany
    {
        return $this->belongsToMany(Industry::class, "attr_theme_industry", 'theme_id', 'industry_id');
    }

    // 价格种类
    public function priceclassify() : BelongsToMany
    {
        return $this->belongsToMany(Priceclassify::class, "attr_theme_priceclassify", 'theme_id', 'priceclassify_id');
    }

    // 地区分类
    public function region() : BelongsToMany
    {
        return $this->belongsToMany(Region::class, "attr_theme_region", 'theme_id', 'region_id');
    }

    // 粉丝量级
    public function fansnumlevel() : BelongsToMany
    {
        return $this->belongsToMany(Fansnumlevel::class, "attr_theme_fansnumlevel", 'theme_id', 'fansnumlevel_id');
    }

    // 阅读量级
    public function readlevel() : BelongsToMany
    {
        return $this->belongsToMany(Readlevel::class, "attr_theme_readlevel", 'theme_id', 'readlevel_id');
    }

    // 点赞量级
    public function likelevel() : BelongsToMany
    {
        return $this->belongsToMany(Likelevel::class, "attr_theme_likelevel", 'theme_id', 'likelevel_id');
    }

    // 价格量级
    public function pricelevel() : BelongsToMany
    {
        return $this->belongsToMany(Pricelevel::class, "attr_theme_pricelevel", 'theme_id', 'pricelevel_id');
    }

    // 权重等级
    public function weightlevel() : BelongsToMany
    {
        return $this->belongsToMany(Weightlevel::class, "attr_theme_weightlevel", 'theme_id', 'weightlevel_id');
    }
}