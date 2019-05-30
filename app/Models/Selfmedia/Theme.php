<?php


namespace App\Models\Selfmedia;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

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