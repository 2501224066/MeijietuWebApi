<?php


namespace App\Models\Weibo;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Weibo\Filed
 *
 * @property int $filed_id 领域id
 * @property string $filed_name 领域名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Filed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Filed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Filed query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Filed whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Filed whereFiledName($value)
 * @mixin \Eloquent
 */
class Filed extends Model
{
    protected $table = 'weibo_filed';

    protected $primaryKey = 'filed_id';

    public $guarded = [];

    public $timestamps =false;
}