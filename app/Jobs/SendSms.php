<?php


namespace App\Jobs;

use App\Models\Captcha;
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

    protected $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $phone, $content)
    {
        $this->type    = $type;
        $this->phone   = $phone;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->type) {
            case Captcha::TYPE['验证码']:
                $ali_sms = new AliSms();
                $ali_sms->sendSms($this->phone, env('ALIYUN_SMS_TEMPLATE_CODE'), ['code' => $this->content]);
                break;
        }
    }
}