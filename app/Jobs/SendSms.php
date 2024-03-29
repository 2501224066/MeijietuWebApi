<?php


namespace App\Jobs;

use App\Server\Captcha;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mrgoon\AliSms\AliSms;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone;

    protected $type;

    protected $contentArr;

    /**
     * SendSms constructor.
     * @param int $type 短信类型
     * @param string $phone 手机号
     * @param array $contentArr 内容
     */
    public function __construct($type, $phone, $contentArr)
    {
        $this->type       = $type;
        $this->phone      = $phone;
        $this->contentArr = $contentArr;
    }

    public function handle()
    {
        $type       = $this->type;
        $phone      = $this->phone;
        $contentArr = $this->contentArr;

        switch ($type) {
            case Captcha::TYPE['验证码']:
                $ali_sms = new AliSms();
                $ali_sms->sendSms($phone,
                    config('services.sms.template_code_a'),
                    ['code' => $contentArr['code']]);
                break;

            case Captcha::TYPE['订单通知']:
                $ali_sms = new AliSms();
                $ali_sms->sendSms($phone,
                    config('services.sms.template_code_b'),
                    [
                        'name'       => $contentArr['name'],
                        'indent_num' => $contentArr['indent_num'],
                        'status'     => $contentArr['status'],
                    ]);
                break;

            case Captcha::TYPE['资金变动']:
                $ali_sms = new AliSms();
                $ali_sms->sendSms($phone,
                    config('services.sms.template_code_c'),
                    [
                        'name'      => $contentArr['name'],
                        'money'     => $contentArr['money'],
                        'direction' => $contentArr['direction'],
                        'amount'    => $contentArr['amount'],
                    ]);
                break;
        }
    }
}