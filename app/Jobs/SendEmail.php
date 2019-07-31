<?php


namespace App\Jobs;

use App\Mail\emailVerifCode;
use App\Server\Captcha;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    protected $type;

    protected $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $email, $content)
    {
        $this->type    = $type;
        $this->email   = $email;
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
                $content = "您的验证码为" . $this->content . ", 有效期5分钟，请在有效期内使用!";
                Mail::to($this->email)
                    ->cc(ENV('MAIL_FROM_ADDRESS'))
                    ->send(new emailVerifCode($content));
                break;
        }
    }
}

{

}