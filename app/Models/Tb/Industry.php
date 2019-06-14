<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $table = "tb_industry";

    protected $primaryKey = 'industry_id';

    public $timestamps = false;

    protected $guarded = [];
}