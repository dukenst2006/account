<?php

namespace App\Users\Auth;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var User */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bible Bowl Account Email Confirmation')
            ->markdown('emails.email-confirmation')
            ->with('user', $this->user);
    }
}
