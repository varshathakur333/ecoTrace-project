<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CollectorVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $collector;

    /**
     * Create a new message instance.
     */
    public function __construct(User $collector)
    {
        $this->collector = $collector;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Collector Verification Required — EcoTrace')
                    ->view('emails.verification');
    }
}
