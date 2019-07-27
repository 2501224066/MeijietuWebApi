<?php


namespace App\Models\Dt;


use Illuminate\Database\Eloquent\Model;



/**
 * App\Models\Dt\Demand
 *
 * @property int $demand_id
 * @property string $demand_num 需求编号
 * @property int $bind_indent_id
 * @property int $uid 媒体主id
 * @property string $title 需求名称
 * @property string|null $word 文档
 * @property string|null $back_link 返回链接
 * @property float|null $price 价格
 * @property int $status 状态 1=等待 2=失效 3=拒绝 4=接受 5=完成  6=结算
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand whereBackLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand whereBindIndentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand whereDemandNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dt\Demand whereWord($value)
 * @mixin \Eloquent
 */
class Demand extends Model
{
    protected $table = 'dt_demand';

    protected $primaryKey = 'demand_id';

    protected $guarded = [];

    const STATUS = [
        '等待' => 1,
        '失效' => 2,
        '拒绝' => 3,
        '接受' => 4,
        '完成' => 5,
        '结算' => 6
    ];
}