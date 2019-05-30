<?php


namespace App\Models\Selfmedia;


use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $table = 'selfmedia_platform';

    protected $primaryKey = 'platform_id';

    public $guarded = [];

    public $timestamps =false;
}