<?php


namespace App\Models\Weibo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

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