<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;


class Priceclassify extends Model
{
    protected $table = "tb_priceclassify";

    protected $primaryKey = 'priceclassify_id';

    public $timestamps = false;

    protected $guarded = [];
}