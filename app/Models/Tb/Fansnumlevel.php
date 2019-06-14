<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;

class Fansnumlevel extends Model
{
    protected $table = "tb_fansnumlevel";

    protected $primaryKey = 'fansnumlevel_id';

    public $timestamps = false;

    protected $guarded = [];
}