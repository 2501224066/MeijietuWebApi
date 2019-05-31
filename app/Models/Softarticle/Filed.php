<?php


namespace App\Models\Softarticle;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Softarticle\Filed
 *
 * @property int $filed_id 领域id
 * @property string $filed_name 领域名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Filed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Filed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Filed query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Filed whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Softarticle\Filed whereFiledName($value)
 * @mixin \Eloquent
 */
class Filed extends Model
{
    protected $table = 'softarticle_filed';

    protected $primaryKey = 'filed_id';

    public $guarded = [];

    public $timestamps =false;
}