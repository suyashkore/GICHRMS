<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FailedLoginAlertMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $requestData;

    public function __construct($user, $requestData)
    {
        $this->user = $user;
        $this->requestData = $requestData;
    }

    public function build()
    {
        return $this->subject('Failed Login Attempt Alert')
                    ->view('emails.failed_login_alert');
    }
}