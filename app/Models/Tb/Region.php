<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tb\Region
 *
 * @property int $region_id 面向地区id
 * @property string $region_name 面向地区
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Region whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Region whereRegionName($value)
 * @mixin \Eloquent
 */
class Region extends Model
{
    protected $table = "tb_region";

    protected $primaryKey = 'region_id';

    public $timestamps = false;

    protected $guarded = [];
}