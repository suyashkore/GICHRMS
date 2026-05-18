<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Mail\ForgotPasswordOtpMail;
use Illuminate\Support\Facades\Mail;

class SendForgotPasswordOtpJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $user,
        public $requestData
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)
            ->send(new ForgotPasswordOtpMail($this->user, $this->requestData));
    }
}
