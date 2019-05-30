<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

class Filed extends Model
{
    protected $table = 'softarticle_filed';

    protected $primaryKey = 'filed_id';

    public $guarded = [];

    public $timestamps =false;
}