<?php


namespace App\Models\dt;


use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $table = 'dt_information';

    protected $primaryKey = 'information_id';

    public $timestamps = false;

    protected $guarded = [];

    public static function indexPageInformation($co)
    {
        return self::offset(0)->limit($co)->orderBy('time', 'DESC')->get();
    }
}