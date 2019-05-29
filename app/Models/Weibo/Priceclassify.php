<?php


namespace App\Models\Weibo;


use Illuminate\Database\Eloquent\Model;

class Priceclassify extends Model
{
    protected $table = 'weibo_priceclassify';

    protected $primaryKey = 'priceclassify_id';

    public $guarded = [];

    public $timestamps =false;
}