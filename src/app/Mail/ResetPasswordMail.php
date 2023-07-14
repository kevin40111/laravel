<?php

namespace App\Mail;

use App\Models\ResetCodePassword;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $link;

    public function __construct(ResetCodePassword $resetPassword)
    {
        $this->link =
            config("global.routes.reset_password") . "/" . $resetPassword->code;
    }

    public function build()
    {
        return $this->markdown("emails.reset-password");
    }
}
