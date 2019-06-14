<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;

class Pricelevel extends Model
{
    protected $table = "tb_pricelevel";

    protected $primaryKey = 'pricelevel_id';

    public $timestamps = false;

    protected $guarded = [];
}