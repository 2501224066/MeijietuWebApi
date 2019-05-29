<?php


namespace App\Models\Weixin;


use Illuminate\Database\Eloquent\Model;

class Readlevel extends Model
{
    protected $table = 'weixin_readlevel';

    protected $primaryKey = 'readlevel_id';

    public $guarded = [];

    public $timestamps =false;
}