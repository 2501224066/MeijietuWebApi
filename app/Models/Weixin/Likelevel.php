<?php


namespace App\Models\Weixin;


use Illuminate\Database\Eloquent\Model;

class Likelevel extends Model
{
    protected $table = 'weixin_likelevel';

    protected $primaryKey = 'likelevel_id';

    public $guarded = [];

    public $timestamps =false;
}