<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class emailVerifCode extends Mailable
{
    use Queueable, SerializesModels;

    protected $codeType;

    protected $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($codeType, $code)
    {
        $this->codeType = $codeType;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.emailVerifCode')
            ->subject($this->codeType)
            ->with([
                'title' => ENV('APP_NAME'),
                'time' => date('Y-m-d'),
                'codeType' => $this->codeType,
                'code'=> $this->code
            ]);
    }
}
