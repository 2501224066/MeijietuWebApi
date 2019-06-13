<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Norms extends Model
{
    protected $table = 'tb_norms';

    protected $primaryKey = 'norms_id';

    public $timestamps = false;

    public function norms_option() : BelongsToMany
    {
        return $this->belongsToMany(NormsOption::class, "tb_norms_norms_option", 'norms_id', 'norms_option_id');
    }
}