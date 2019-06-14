<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $table = "tb_platform";

    protected $primaryKey = 'platform_id';

    public $timestamps = false;

    protected $guarded = [];
}