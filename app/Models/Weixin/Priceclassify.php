<?php


namespace App\Models\Weixin;


use Illuminate\Database\Eloquent\Model;

class Priceclassify extends Model
{
    protected $table = 'weixin_priceclassify';

    protected $primaryKey = 'priceclassify_id';

    public $guarded = [];

    public $timestamps =false;
}