<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $table = 'softarticle_platform';

    protected $primaryKey = 'platform_id';

    public $guarded = [];

    public $timestamps =false;
}