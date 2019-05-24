<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;

class RealnamePeople extends Model
{
    protected $table = 'realname_people';

    // 银行卡四要素查询
    public static function checkBankInfo($acct_name, $acct_pan, $cert_id, $phone_num)
    {
        $data = getBankInfo($acct_name, $acct_pan, $cert_id, $phone_num);
        $data = json_decode($data);
        if ( ! $data->showapi_res_body->msg == "认证通过")
            throw new Exception("【真实姓名】，【银行卡号】，【身份证号码】，【绑定手机号】中有错误");

        return true;
    }

}