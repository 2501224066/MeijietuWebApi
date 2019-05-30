<?php


namespace App\Models\Softarticle;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

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
}