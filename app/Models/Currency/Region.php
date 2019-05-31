<?php


namespace App\Models\Currency;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Currency\Region
 *
 * @property int $region_id 面向地区id
 * @property string $region_name 面向地区
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency\Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency\Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency\Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency\Region whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency\Region whereRegionName($value)
 * @mixin \Eloquent
 */
class Region extends Model
{
    protected $table = 'currency_region';

    protected $primaryKey = 'region_id';

    public $guarded = [];

    public $timestamps =false;
}