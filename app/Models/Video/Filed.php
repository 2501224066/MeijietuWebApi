<?php


namespace App\Models\Video;


use Illuminate\Database\Eloquent\Model;

class Filed extends Model
{
    protected $table = 'video_filed';

    protected $primaryKey = 'filed_id';

    public $guarded = [];

    public $timestamps =false;
}