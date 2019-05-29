<?php


namespace App\Models\Weibo;


use Illuminate\Database\Eloquent\Model;

class Authtype extends Model
{
    protected $table = 'weibo_authtype';

    protected $primaryKey = 'authtype_id';

    public $guarded = [];

    public $timestamps =false;
}