<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;

class Filed extends Model
{
    protected $table = "tb_filed";

    protected $primaryKey = 'filed_id';

    public $timestamps = false;

    protected $guarded = [];
}