<?php


namespace App\Models\Realname;


use App\Models\System\Setting;
use DemeterChain\C;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Realname\RealnameEnterprise
 *
 * @property int $uid
 * @property string $enterprise_name 公司名称
 * @property string $social_credit_code 统一社会信用代码
 * @property string $business_license 营业执照图片
 * @property string $bank_deposit 开户银行
 * @property string $bank_branch 开户支行
 * @property string $bank_porv 开户省
 * @property string $bank_city 开户城市
 * @property string $bank_card 银行卡号
 * @property string $bank_band_phone 绑定手机号
 * @property int $verify_status 审核状态 0=失败 1=成功
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereBankBandPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereBankBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereBankCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereBankCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereBankDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereBankPorv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereBusinessLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereEnterpriseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereSocialCreditCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereVerifyStatus($value)
 * @mixin \Eloquent
 * @property string $bank_prov 开户省
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Realname\RealnameEnterprise whereBankProv($value)
 */
class RealnameEnterprise extends Model
{
    protected $table = 'realname_enterprise';

    public $guarded = [];

    /**
     * 检查营业执照信息
     * @param string $business_license 营业执照存储地址
     * @param string $enterprise_name 企业名称
     * @param string $social_credit_code 统一社会信用代码
     */
    public static function checkBusinessLicense($business_license, $enterprise_name, $social_credit_code)
    {
        if (!Storage::exists($business_license))
            throw new Exception("获取营业执照图片失败");

        // 营业执照图片转base64编码
        $img_content = file_get_contents(Setting::staticUrl() . $business_license);
        $img_base64  = urlencode(base64_encode($img_content));

        // 请求营业执照信息外部接口
        $data = businessLicense_API($img_base64);
        if ($data['name'] != $enterprise_name)
            throw new Exception("企业名称与营业执照上信息不符");
        if ($data['credit'] != $social_credit_code)
            throw new Exception("统一社会信用代码与营业执照上信息不符");
    }

    /**
     * 添加企业认证信息
     * @param mixed $request 表单信息
     * @throws \Throwable
     */
    public static function add($request)
    {
        if (!Storage::exists($request->business_license))
            throw new Exception("获取营业执照图片失败");

        DB::transaction(function () use ($request) {
            try {
                $uid = JWTAuth::user()->uid;

                // 添加企业实名认证数据
                DB::table('realname_enterprise')
                    ->insert([
                        'uid'                => $uid,
                        'enterprise_name'    => htmlspecialchars($request->enterprise_name),
                        'social_credit_code' => htmlspecialchars($request->social_credit_code),
                        'business_license'   => htmlspecialchars($request->business_license),
                        'bank_deposit'       => htmlspecialchars($request->bank_deposit),
                        'bank_branch'        => htmlspecialchars($request->bank_branch),
                        'bank_prov'          => htmlspecialchars($request->bank_prov),
                        'bank_city'          => htmlspecialchars($request->bank_city),
                        'bank_card'          => htmlspecialchars($request->bank_card),
                        'bank_band_phone'    => htmlspecialchars($request->bank_band_phone),
                        'verify_status'      => 1, // 审核状态 0=未通过 1=审核通过
                        'created_at'         => date('Y-m-d H:i:s'),
                        'updated_at'         => date('Y-m-d H:i:s')
                    ]);


                // 修改用户表中实名认证状态
                DB::table('user')
                    ->where('uid', $uid)
                    ->update([
                        'realname_status' => 2 // 实名认证状态 0=未认证 1=个人认证 2=企业认证
                    ]);

            } catch (\Exception $e) {
                throw new Exception('保存失败');
            }
        });
    }

    /**
     * 获取企业认证信息
     * @return array
     */
    public static function info(): array
    {
        $uid  = JWTAuth::user()->uid;
        $data = self::whereUid($uid)->first();
        if (!$data)
            throw new Exception("未查询到认证信息");

        return [
            'enterprise_name'    => $data->enterprise_name,
            'social_credit_code' => preg_replace("/(.{4}).{9}(.{5})/", "\$1*********\$2", $data->social_credit_code),
            'bank_band_phone'    => preg_replace("/(\d{3})\d{4}(\d{4})/", "\$1****\$2", $data->bank_band_phone),
            'bank_deposit'       => $data->bank_deposit,
            'bank_branch'        => $data->bank_branch,
            'bank_where'         => $data->bank_prov . $data->bank_city,
            'bank_card'          => preg_replace("/(\d{4})\d{12}(\d{3})/", "\$1 **** **** **** \$2", $data->bank_card)
        ];
    }

}