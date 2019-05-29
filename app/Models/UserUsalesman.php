<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;

/**
 * App\Models\UserUsalesman
 *
 * @property int $uid 用户id
 * @property int $salesman_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserUsalesman newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserUsalesman newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserUsalesman query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserUsalesman whereSalesmanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserUsalesman whereUid($value)
 * @mixin \Eloquent
 */
class UserUsalesman extends Model
{
    protected $table = 'user_usalesman';

    public $timestamps = false;

    public $guarded = [];

    // 获取客服ID
    public static function getSalesmanId($uid)
    {
        $data = self::whereUid($uid)->first();
        if( ! $data)
            throw new Exception('未分配客服');

        return $data->salesman_id;
    }
}