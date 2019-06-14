<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;


class Likelevel extends Model
{
    protected $table = "tb_likelevel";

    protected $primaryKey = 'likelevel_id';

    public $timestamps = false;

    protected $guarded = [];
}