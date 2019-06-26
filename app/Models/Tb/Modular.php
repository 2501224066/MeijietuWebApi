<?php


namespace App\Models\Tb;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Tb\Modular
 *
 * @property int $modular_id
 * @property string $modular_name 模块名称
 * @property string $tag 模块标记
 * @property string $abbreviation 缩写
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tb\Theme[] $theme
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular whereAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular whereModularId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular whereModularName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular whereTag($value)
 * @mixin \Eloquent
 * @property int $settlement_type 结算方式 1=标准模式 2=软文模式
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tb\Modular whereSettlementType($value)
 */
class Modular extends Model
{
    protected $table = 'tb_modular';

    protected $primaryKey = 'modular_id';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * 结算模式
     * 标准模式：订单价格-（订单价格*服务费率+官方成本） =  卖家获利
     * 软文秘书：底价 = 卖家获利
     */
    const SETTLEMENT_TYPE = [
        '标准模式' => 1,
        '软文模式' => 2
    ];

    const TAG = [
        '微信营销'  => 'WEIXIN',
        '微博营销'  => 'WEIBO',
        '视频营销'  => 'VIDEO',
        '自媒体营销' => 'SELFMEIDA',
        '软文营销'  => 'SOFTARTICLE'
    ];

    public function theme(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'tb_modular_theme', 'modular_id', 'theme_id');
    }
}