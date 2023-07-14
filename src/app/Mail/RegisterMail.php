<?php

namespace App\Mail;

use App\Models\UserRegister;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $link;

    public function __construct(UserRegister $register)
    {
        $this->link = config("global.routes.confirm") . "/" . $register->token;
    }

    public function build()
    {
        return $this->markdown("emails.register");
    }
}
