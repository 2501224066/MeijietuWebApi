<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

class Pricelevel extends Model
{
    protected $table = 'softarticle_pricelevel';

    protected $primaryKey = 'pricelevel_id';

    public $guarded = [];

    public $timestamps =false;
}