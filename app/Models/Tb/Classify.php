<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Classify extends Model
{
    protected $table = 'tb_classify';

    protected $primaryKey = 'classify_id';

    protected $guarded = [];

    public function attr() : BelongsToMany
    {
        return $this->belongsToMany(Attr::class, "tb_classify_attr", 'classify_id', 'attr_id');
    }

    public function norms() : BelongsToMany
    {
        return $this->belongsToMany(Norms::class, "tb_classify_norms", 'classify_id', 'norms_id');
    }

    public function level() : BelongsToMany
    {
        return $this->belongsToMany(Level::class, "tb_classify_level", 'classify_id', 'level_id');
    }

}