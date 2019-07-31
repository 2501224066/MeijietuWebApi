<?php


namespace App\Models\Data;


use Illuminate\Database\Eloquent\Model;



/**
 * App\Models\Data\Demand
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand whereBackLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand whereBindIndentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand whereDemandNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Demand whereWord($value)
 * @mixin \Eloquent
 */
class Demand extends Model
{
    protected $table = 'data_demand';

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