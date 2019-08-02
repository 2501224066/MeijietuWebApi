<?php


namespace App\Http\Controllers\v1;


use App\Jobs\SendSms;
use App\Server\Captcha;

class TestController extends BaseController
{

    function index()
    {

        SendSms::dispatch(Captcha::TYPE['资金变动'],
            '18827335317',
            [
                'name'      => "皮皮",
                'money'     => "10023",
                'direction' => "转入",
                'amount'    => "2000"
            ])->onQueue('SendSms');

        // TODO...
    }
}
