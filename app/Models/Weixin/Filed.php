<?php


namespace App\Models\Weixin;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Weixin\Filed
 *
 * @property int $filed_id 领域id
 * @property string $filed_name 领域名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Filed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Filed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Filed query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Filed whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weixin\Filed whereFiledName($value)
 * @mixin \Eloquent
 */
class Filed extends Model
{
    protected $table = 'weixin_filed';

    protected $primaryKey = 'filed_id';

    public $guarded = [];

    public $timestamps =false;
}