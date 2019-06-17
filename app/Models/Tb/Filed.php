<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tb\Filed
 *
 * @property int $filed_id 领域id
 * @property string $filed_name 领域名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Filed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Filed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Filed query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Filed whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Filed whereFiledName($value)
 * @mixin \Eloquent
 */
class Filed extends Model
{
    protected $table = "tb_filed";

    protected $primaryKey = 'filed_id';

    public $timestamps = false;

    protected $guarded = [];
}