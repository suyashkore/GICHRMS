<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountBlockedAlertMail extends Mailable
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
        return $this->subject('Account Blocked Alert')
                    ->view('emails.account_blocked_alert');
    }
}