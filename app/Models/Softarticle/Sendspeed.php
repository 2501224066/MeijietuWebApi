<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Softarticle\Sendspeed
 *
 * @property int $sendspeed_id 发稿速度id
 * @property string $sendspeed_name 发稿速度名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Sendspeed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Sendspeed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Sendspeed query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Sendspeed whereSendspeedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Sendspeed whereSendspeedName($value)
 * @mixin \Eloquent
 */
class Sendspeed extends Model
{
    protected $table = 'softarticle_sendspeed';

    protected $primaryKey = 'sendspeed_id';

    public $guarded = [];

    public $timestamps =false;
}