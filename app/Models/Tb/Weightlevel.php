<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;

class Weightlevel extends Model
{
    protected $table = "tb_weightlevel";

    protected $primaryKey = 'weightlevel_id';

    public $timestamps = false;

    protected $guarded = [];
}