<?php


namespace App\Models\Weixin;


use Illuminate\Database\Eloquent\Model;

class Filed extends Model
{
    protected $table = 'weixin_filed';

    protected $primaryKey = 'filed_id';

    public $guarded = [];

    public $timestamps =false;
}