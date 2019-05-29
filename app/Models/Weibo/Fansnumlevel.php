<?php


namespace App\Models\Weibo;


use Illuminate\Database\Eloquent\Model;

class Fansnumlevel extends Model
{
    protected $table = 'weibo_fansnumlevel';

    protected $primaryKey = 'fansnumlevel_id';

    public $guarded = [];

    public $timestamps =false;
}