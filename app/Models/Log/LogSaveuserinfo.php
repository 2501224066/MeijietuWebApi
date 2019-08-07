<?php


namespace App\Models\Log;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Log\LogSaveuserinfo
 *
 * @property int $log_saveuserinfo_id
 * @property int $uid 用户id
 * @property string $ip 客户端IP
 * @property string $old_info 原信息
 * @property string $new_info 新信息
 * @property string|null $time_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogSaveuserinfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogSaveuserinfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogSaveuserinfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogSaveuserinfo whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogSaveuserinfo whereLogSaveuserinfoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogSaveuserinfo whereNewInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogSaveuserinfo whereOldInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogSaveuserinfo whereTimeAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogSaveuserinfo whereUid($value)
 * @mixin \Eloquent
 * @property string $save_info 原信息
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogSaveuserinfo whereSaveInfo($value)
 */
class LogSaveuserinfo extends Model
{
    protected $table = 'log_saveuserinfo';

    protected $primaryKey = 'log_saveuserinfo_id';

    public $timestamps = false;

    public $guarded = [];


}