<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;

class RealnamePeople extends Model
{
    protected $table = 'realname_people';

    public $guarded = [];

    const VERIFY_TYPE = [
        0 => '审核失败',
        1 => '审核成功'
    ];

    // 银行卡四要素查询
    public static function checkBankInfo($acct_name, $acct_pan, $cert_id, $phone_num)
    {
        $data = getBankInfo($acct_name, $acct_pan, $cert_id, $phone_num);
        $data = json_decode($data);
        if ( ! $data->showapi_res_body->msg == "认证通过")
            throw new Exception("【真实姓名】，【银行卡号】，【身份证号码】，【绑定手机号】中有错误");

        return true;
    }

    // 证件号验证
    public static function IDcheck($identity_card_face, $truename)
    {
        if( ! Storage::exists($identity_card_face) )
            throw new Exception("获取身份证正面图片失败");

        // 身份证正面图片转base64编码
        $img_content = file_get_contents(env('ALIOSS_URL').$identity_card_face);
        $img_base64 = base64_encode($img_content);

        $data = getIDcheck($img_base64);
        $data = json_decode($data);

        if ( ! $data->message->value == "识别完成")
            throw new Exception("身份证识别失败");

        if ( ! $data->cardsinfo[0]->items[1]->content == $truename)
            throw new Exception("身份证与个人信息不匹配");

        return true;
    }

    // 添加个人认证信息
    public static function add($request)
    {
        if( ! Storage::exists($request->identity_card_face) )
            throw new Exception("获取身份证正面图片失败");
        if( ! Storage::exists($request->identity_card_back) )
            throw new Exception("获取身份证背面图片失败");


        $re = self::create([
            'uid' => JWTAuth::user()->uid,
            'truename' => htmlspecialchars($request->truename),
            'identity_card_ID' => htmlspecialchars($request->identity_card_ID),
            'identity_card_face' => htmlspecialchars($request->identity_card_face),
            'identity_card_back' => htmlspecialchars($request->identity_card_back),
            'bank_deposit' => htmlspecialchars($request->bank_deposit),
            'bank_branch' => htmlspecialchars($request->bank_branch),
            'bank_prov' => htmlspecialchars($request->bank_prov),
            'bank_city' => htmlspecialchars($request->bank_cit),
            'bank_card' => htmlspecialchars($request->bank_card),
            'bank_band_phone' => htmlspecialchars($request->bank_band_phone),
            'verify_status' => 1
        ]);
        if ( ! $re)
            throw new Exception("保存失败");

        return true;
    }

}