<?php


namespace App\Models\Video;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Video\Filed
 *
 * @property int $filed_id 领域id
 * @property string|null $filed_name 领域名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Filed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Filed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Filed query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Filed whereFiledId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Filed whereFiledName($value)
 * @mixin \Eloquent
 */
class Filed extends Model
{
    protected $table = 'video_filed';

    protected $primaryKey = 'filed_id';

    public $guarded = [];

    public $timestamps =false;
}