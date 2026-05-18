<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Mail\AccountBlockedAlertMail;
use Illuminate\Support\Facades\Mail;

class SendAccountBlockedAlertJob implements ShouldQueue
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
            ->send(new AccountBlockedAlertMail($this->user, $this->requestData));
    }
}
