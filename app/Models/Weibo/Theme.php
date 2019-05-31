<?php


namespace App\Models\Weibo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Weibo\Theme
 *
 * @property int $theme_id
 * @property string $theme_name 主题名称
 * @property int $theme_status 主题状态 0=禁用 1=启用
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Weibo\Authtype[] $authtype
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Weibo\Fansnumlevel[] $fansnumlevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Weibo\Filed[] $filed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Weibo\Priceclassify[] $priceclassify
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Theme query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Theme whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Theme whereThemeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Theme whereThemeStatus($value)
 * @mixin \Eloquent
 */
class Theme extends Model
{
    protected $table = 'weibo_theme';

    protected $primaryKey = 'theme_id';

    public $guarded = [];

    public $timestamps =false;

    public function filed() : BelongsToMany
    {
        return $this->belongsToMany(Filed::class, "weibo_theme_filed", 'theme_id', 'filed_id');
    }

    public function fansnumlevel() : BelongsToMany
    {
        return $this->belongsToMany(Fansnumlevel::class, "weibo_theme_fansnumlevel", 'theme_id', 'fansnumlevel_id');
    }

    public function priceclassify() : BelongsToMany
    {
        return $this->belongsToMany(Priceclassify::class, "weibo_theme_priceclassify", 'theme_id', 'priceclassify_id');
    }

    public function authtype() : BelongsToMany
    {
        return $this->belongsToMany(Authtype::class, "weibo_theme_authtype", 'theme_id', 'authtype_id');
    }

}