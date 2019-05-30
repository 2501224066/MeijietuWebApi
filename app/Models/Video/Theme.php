<?php


namespace App\Models\Video;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

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