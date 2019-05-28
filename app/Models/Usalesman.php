<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Usalesman extends Model
{
    protected $table = 'usalesman';

    public $guarded = [];

    // 循环获取客服id
    public static function getSalesman()
    {
        // 位置不存在设置初始值
        if ( ! Cache::has('salesman_tag'))
            Cache::put('salesman_tag', 1000000);

        $id = self::where('salesman_id', '>', Cache::get('salesman_tag'))->value('salesman_id');

        // 位置超出回归初始值
        if ( ! $id)
            Cache::put('salesman_tag', 1000000);

        Cache::increment('salesman_tag', $id);

        return $id;
    }


}