<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

class Entryclassify extends Model
{
    protected $table = 'softarticle_entryclassify';

    protected $primaryKey = 'entryclassify_id';

    public $guarded = [];

    public $timestamps =false;
}