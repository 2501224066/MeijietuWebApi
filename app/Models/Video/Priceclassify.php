<?php


namespace App\Models\Video;


use Illuminate\Database\Eloquent\Model;

class Priceclassify extends Model
{
    protected $table = 'video_priceclassify';

    protected $primaryKey = 'priceclassify_id';

    public $guarded = [];

    public $timestamps =false;
}