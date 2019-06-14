<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = "tb_region";

    protected $primaryKey = 'region_id';

    public $timestamps = false;

    protected $guarded = [];
}