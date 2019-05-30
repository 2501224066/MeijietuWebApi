<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $table = 'softarticle_industry';

    protected $primaryKey = 'industry_id';

    public $guarded = [];

    public $timestamps =false;
}