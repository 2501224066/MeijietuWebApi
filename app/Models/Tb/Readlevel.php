<?php


namespace App\Models\Tb;

use Illuminate\Database\Eloquent\Model;

class Readlevel extends Model
{
    protected $table = "tb_readlevel";

    protected $primaryKey = 'readlevel_id';

    public $timestamps = false;

    protected $guarded = [];
}