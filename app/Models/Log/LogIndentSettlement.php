<?php


namespace App\Models\Log;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Log\LogIndentSettlement
 *
 * @property string $indent_num 结算失败订单号
 * @property string $created_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogIndentSettlement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogIndentSettlement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogIndentSettlement query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogIndentSettlement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogIndentSettlement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\LogIndentSettlement whereIndentNum($value)
 * @mixin \Eloquent
 */
class LogIndentSettlement extends Model
{
    protected $table = 'log_indentsettlement';

    protected $guarded = [];

    public $timestamps = false;
}