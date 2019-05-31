<?php


namespace App\Models\Weibo;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Weibo\Authtype
 *
 * @property int $authtype_id 认证类型id
 * @property string $authtype_name 认证类型名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Authtype newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Authtype newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Authtype query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Authtype whereAuthtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weibo\Authtype whereAuthtypeName($value)
 * @mixin \Eloquent
 */
class Authtype extends Model
{
    protected $table = 'weibo_authtype';

    protected $primaryKey = 'authtype_id';

    public $guarded = [];

    public $timestamps =false;
}