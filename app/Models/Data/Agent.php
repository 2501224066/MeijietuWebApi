<?php


namespace App\Models\Data;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Data\Agent
 *
 * @property int $id
 * @property string $agent_num 代理编号/账号
 * @property string $password 密码
 * @property string $contacts 联系人
 * @property string $phone 联系电话
 * @property string $domain 域名
 * @property string|null $authorize_maturity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Agent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Agent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Agent query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Agent whereAgentNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Agent whereAuthorizeMaturity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Agent whereContacts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Agent whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Agent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Agent wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Data\Agent wherePhone($value)
 * @mixin \Eloquent
 */
class Agent extends Model
{
    protected $table = 'data_agent';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];
}