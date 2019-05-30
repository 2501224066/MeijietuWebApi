<?php


namespace App\Models\Selfmedia;


use Illuminate\Database\Eloquent\Model;

class Filed extends Model
{
    protected $table = 'selfmedia_filed';

    protected $primaryKey = 'filed_id';

    public $guarded = [];

    public $timestamps =false;
}