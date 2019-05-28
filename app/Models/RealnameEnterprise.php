<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class RealnameEnterprise extends Model
{
    protected $table = 'realname_enterprise';

    public $guarded = [];

    // 检查营业执照信息
    public static function checkBusinessLicense($business_license, $enterprise_name, $social_credit_code)
    {
        if( ! Storage::exists($business_license) )
            throw new Exception("获取营业执照图片失败");

        // 营业执照图片转base64编码
        $img_content = file_get_contents(env('ALIOSS_URL').$business_license);
        $img_base64 = base64_encode($img_content);

        // 请求营业执照信息外部接口
        $data = businessLicense_API($img_base64);
        if ($data->name != $enterprise_name)
            throw new Exception("企业名称与营业执照上信息不符");
        if ($data->credit != $social_credit_code)
            throw new Exception("统一社会信用代码与营业执照上信息不符");

        return true;
    }

    // 添加企业认证信息
    public static function add($request)
    {
        if( ! Storage::exists($request->business_license) )
            throw new Exception("获取营业执照图片失败");

        DB::transaction(function () use ($request) {
            $uid = JWTAuth::user()->uid;

            // 添加个人实名认证数据
            DB::table('realname_enterprise')
                ->insert([
                    'uid' => $uid,
                    'enterprise_name' => htmlspecialchars($request->enterprise_name),
                    'social_credit_code' => htmlspecialchars($request->social_credit_code),
                    'business_license' => htmlspecialchars($request->business_license),
                    'bank_deposit' => htmlspecialchars($request->bank_deposit),
                    'bank_branch' => htmlspecialchars($request->bank_branch),
                    'bank_prov' => htmlspecialchars($request->bank_prov),
                    'bank_city' => htmlspecialchars($request->bank_cit),
                    'bank_card' => htmlspecialchars($request->bank_card),
                    'bank_band_phone' => htmlspecialchars($request->bank_band_phone),
                    'verify_status' => 1 // 审核状态 0=未通过 1=审核通过
                ]);

            // 修改用户表中实名认证状态
            DB::table('user')
                ->where('uid', $uid)
                ->update([
                    'realname_status' => 2 // 实名认证状态 0=未认证 1=个人认证 2=企业认证
                ]);

            return true;
        });

        throw new Exception("保存失败");
    }
}