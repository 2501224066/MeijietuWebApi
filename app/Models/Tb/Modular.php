<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Tb\Modular
 *
 * @property int $modular_id
 * @property string $modular_name 模块名称
 * @property string $tag 模块标记
 * @property string $abbreviation 缩写
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tb\Theme[] $theme
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular whereAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular whereModularId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular whereModularName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular whereTag($value)
 * @mixin \Eloquent
 */
class Modular extends Model
{
    protected $table = 'tb_modular';

    protected $primaryKey = 'modular_id';

    protected $guarded = [];

    public $timestamps = false;

    public function theme() : BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'tb_modular_theme', 'modular_id', 'theme_id');
    }
}