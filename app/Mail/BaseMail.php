<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BaseMail extends Mailable
{

    use Queueable, SerializesModels;

    public function __construct()
    {

	}
    public static function getSmtpSettings()
    {

    }

}
