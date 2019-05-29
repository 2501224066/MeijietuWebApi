<?php


namespace App\Models\Weixin;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Theme
 *
 * @property int $theme_id 主题id
 * @property string $theme_name 主题名称
 * @property int $theme_status 主题状态 0=禁用 1=启用
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Theme query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Theme whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Theme whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Theme whereThemeStatus($value)
 * @mixin \Eloquent
 */
class Theme extends Model
{
    protected $table = 'weixin_theme';

    protected $primaryKey = 'theme_id';

    public $guarded = [];

    public $timestamps =false;

    public function filed() : BelongsToMany
    {
        return $this->belongsToMany(Filed::class, "weixin_theme_filed", 'theme_id', 'filed_id');
    }

    public function fansnumlevel() : BelongsToMany
    {
        return $this->belongsToMany(Fansnumlevel::class, "weixin_theme_fansnumlevel", 'theme_id', 'fansnumlevel_id');
    }

    public function readlevel() : BelongsToMany
    {
        return $this->belongsToMany(Readlevel::class, "weixin_theme_readlevel", 'theme_id', 'readlevel_id');
    }

    public function likelevel() : BelongsToMany
    {
        return $this->belongsToMany(Likelevel::class, "weixin_theme_likelevel", 'theme_id', 'likelevel_id');
    }

    public function priceclassify() : BelongsToMany
    {
        return $this->belongsToMany(Priceclassify::class, "weixin_theme_priceclassify", 'theme_id', 'priceclassify_id');
    }

}