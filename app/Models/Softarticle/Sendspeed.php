<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

class Sendspeed extends Model
{
    protected $table = 'softarticle_sendspeed';

    protected $primaryKey = 'sendspeed_id';

    public $guarded = [];

    public $timestamps =false;
}