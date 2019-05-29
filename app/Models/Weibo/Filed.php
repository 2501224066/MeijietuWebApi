<?php


namespace App\Models\Weibo;


use Illuminate\Database\Eloquent\Model;

class Filed extends Model
{
    protected $table = 'weibo_filed';

    protected $primaryKey = 'filed_id';

    public $guarded = [];

    public $timestamps =false;
}