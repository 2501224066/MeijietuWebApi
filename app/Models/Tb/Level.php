<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Level extends Model
{
    protected $table = 'tb_level';

    protected $primaryKey = 'level_id';

    public $timestamps = false;

    public function level_option() : BelongsToMany
    {
        return $this->belongsToMany(LevelOption::class, "tb_level_level_option", 'level_id', 'level_option_id');
    }
}