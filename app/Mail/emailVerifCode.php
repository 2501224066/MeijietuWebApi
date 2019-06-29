<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class emailVerifCode extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.emailVerifCode')
            ->subject('通知邮件')
            ->with([
                'title' => ENV('APP_NAME'),
                'time' => date('Y-m-d'),
                'content' => $this->content,
            ]);
    }
}
