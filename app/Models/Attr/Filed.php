<?php


namespace App\Models\Attr;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tb\Filed
 *
 * @property int $filed_id 领域id
 * @property string $filed_name 领域名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Filed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Filed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Filed query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Filed whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attr\Filed whereFiledName($value)
 * @mixin \Eloquent
 */
class Filed extends Model
{
    protected $table = "attr_filed";

    protected $primaryKey = 'filed_id';

    public $timestamps = false;

    protected $guarded = [];
}