<?php


namespace App\Models\Weixin;


use Illuminate\Database\Eloquent\Model;

class Fansnumlevel extends Model
{
    protected $table = 'weixin_fansnumlevel';

    protected $primaryKey = 'fansnumlevel_id';

    public $guarded = [];

    public $timestamps =false;
}