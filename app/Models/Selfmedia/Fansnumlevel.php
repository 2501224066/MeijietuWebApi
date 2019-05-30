<?php


namespace App\Models\Selfmedia;


use Illuminate\Database\Eloquent\Model;

class Fansnumlevel extends Model
{
    protected $table = 'selfmedia_fansnumlevel';

    protected $primaryKey = 'fansnumlevel_id';

    public $guarded = [];

    public $timestamps =false;
}