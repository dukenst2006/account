<?php namespace BibleBowl\Users\Auth;

use BibleBowl\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendConfirmationEmail
{

    /**
     * Handle the event.
     *
     * @param  User  $user
     * @return void
     */
    public function handle(User $user)
    {
        if ($user->status == User::STATUS_UNCONFIRMED) {
            Mail::queue(
                'emails.email-confirmation',
                [
                    'user' => $user
                ],
                function (Message $message) use ($user) {
                    $message->to($user->email, $user->full_name)
                        ->subject('Bible Bowl Account Email Confirmation');
                }
            );
        }
    }
}
