<?php


namespace App\Models\Selfmedia;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Selfmedia\Filed
 *
 * @property int $filed_id 领域id
 * @property string $filed_name 领域名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Filed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Filed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Filed query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Filed whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Selfmedia\Filed whereFiledName($value)
 * @mixin \Eloquent
 */
class Filed extends Model
{
    protected $table = 'selfmedia_filed';

    protected $primaryKey = 'filed_id';

    public $guarded = [];

    public $timestamps =false;
}