<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;


/**
 * App\Models\Usalesman
 *
 * @property int $salesman_id 业务员id （初始值1000000）
 * @property string $salesman_qq_ID QQ号
 * @property string $salesman_weixin_ID 微信号
 * @property string $salesman_name 姓名
 * @property string $salesman_head_portrait 头像
 * @property int $status 状态 0=禁用 1=启用
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman whereSalesmanHeadPortrait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman whereSalesmanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman whereSalesmanName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman whereSalesmanQqID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman whereSalesmanWeixinID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $account 账号
 * @property string $password 密码
 * @property string $true_pass 真实密码
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usalesman whereTruePass($value)
 */
class Usalesman extends Model
{
    protected $table = 'usalesman';

    public $guarded = [];

    // 循环获取客服id
    public static function getSalesman()
    {
        // 位置初始值
        $init = self::whereStatus(1)->orderBy('salesman_id', 'ASC')->first()->salesman_id;

        // 位置不存在设置初始值
        if ( ! Cache::has('salesman_tag')) {
            Cache::put('salesman_tag', $init, 60);
            return $init;
        }

        $id = self::whereStatus(1)->where('salesman_id', '>', Cache::get('salesman_tag'))->value('salesman_id');

        // 位置超出回归初始值
        if ( ! $id) {
            Cache::put('salesman_tag', $init, 60);
            return $init;
        }

        Cache::put('salesman_tag', intval($id), 60);

        return $id;
    }

    // 获取客服信息
    public static function info($salesman_id)
    {
        $re = self::whereSalesmanId($salesman_id)->first();
        if( ! $re)
            throw new Exception('未找到客服信息');

        return $re;
    }

}