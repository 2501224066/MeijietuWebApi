<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attr extends Model
{
    protected $table = 'tb_attr';

    protected $primaryKey = 'attr_id';

    public $timestamps = false;

    protected $guarded = [];

    public function attr_option() : BelongsToMany
    {
        return $this->belongsToMany(AttrOption::class, "tb_attr_attr_option", 'attr_id', 'attr_option_id');
    }
}