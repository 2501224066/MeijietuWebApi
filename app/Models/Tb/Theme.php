<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Tb\Theme
 *
 * @property int $theme_id
 * @property string $theme_name
 * @property string $theme_status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tb\Fansnumlevel[] $fansnumlevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tb\Filed[] $filed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tb\Industry[] $industry
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tb\Likelevel[] $likelevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tb\Platform[] $platform
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tb\Priceclassify[] $priceclassify
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tb\Pricelevel[] $pricelevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tb\Readlevel[] $readlevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tb\Region[] $region
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tb\Weightlevel[] $weightlevel
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Theme query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Theme whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Theme whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Theme whereThemeStatus($value)
 * @mixin \Eloquent
 */
class Theme extends Model
{
    protected $table = 'tb_theme';

    protected $primaryKey = 'theme_id';

    protected $guarded = [];

    public $timestamps = false;

    // 领域
    public function filed() : BelongsToMany
    {
        return $this->belongsToMany(Filed::class, "tb_theme_filed", 'theme_id', 'filed_id');
    }

    // 平台
    public function platform() : BelongsToMany
    {
        return $this->belongsToMany(Platform::class, "tb_theme_platform", 'theme_id', 'platform_id');
    }

    // 行业
    public function industry() : BelongsToMany
    {
        return $this->belongsToMany(Industry::class, "tb_theme_industry", 'theme_id', 'industry_id');
    }

    // 价格种类
    public function priceclassify() : BelongsToMany
    {
        return $this->belongsToMany(Priceclassify::class, "tb_theme_priceclassify", 'theme_id', 'priceclassify_id');
    }

    // 地区分类
    public function region() : BelongsToMany
    {
        return $this->belongsToMany(Region::class, "tb_theme_region", 'theme_id', 'region_id');
    }

    // 粉丝量级
    public function fansnumlevel() : BelongsToMany
    {
        return $this->belongsToMany(Fansnumlevel::class, "tb_theme_fansnumlevel", 'theme_id', 'fansnumlevel_id');
    }

    // 阅读量级
    public function readlevel() : BelongsToMany
    {
        return $this->belongsToMany(Readlevel::class, "tb_theme_readlevel", 'theme_id', 'readlevel_id');
    }

    // 点赞量级
    public function likelevel() : BelongsToMany
    {
        return $this->belongsToMany(Likelevel::class, "tb_theme_likelevel", 'theme_id', 'likelevel_id');
    }

    // 价格量级
    public function pricelevel() : BelongsToMany
    {
        return $this->belongsToMany(Pricelevel::class, "tb_theme_pricelevel", 'theme_id', 'pricelevel_id');
    }

    // 权重等级
    public function weightlevel() : BelongsToMany
    {
        return $this->belongsToMany(Weightlevel::class, "tb_theme_weightlevel", 'theme_id', 'weightlevel_id');
    }
}