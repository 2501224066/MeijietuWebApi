<?php


namespace App\Models\Video;


use Illuminate\Database\Eloquent\Model;

class Fansnumlevel extends Model
{
    protected $table = 'video_fansnumlevel';

    protected $primaryKey = 'fansnumlevel_id';

    public $guarded = [];

    public $timestamps =false;
}