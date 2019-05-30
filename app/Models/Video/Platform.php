<?php


namespace App\Models\Video;


use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $table = 'video_platform';

    protected $primaryKey = 'platform_id';

    public $guarded = [];

    public $timestamps =false;
}