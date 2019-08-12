<?php


namespace App\Models\Count;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Count\Day
 *
 * @property int $id
 * @property int $register 注册数量
 * @property int $goods 商品数量
 * @property int $indent 添加订单数量
 * @property int $demand 添加需求数量
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count\Day newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count\Day newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count\Day query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count\Day whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count\Day whereDemand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count\Day whereGoods($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count\Day whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count\Day whereIndent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count\Day whereRegister($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count\Day whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count\Day whereTime($value)
 */
class Day extends Model
{
    protected $table = 'count_day';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;
}