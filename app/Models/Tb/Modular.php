<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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