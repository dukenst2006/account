<?php namespace BibleBowl\Users\Auth;

use BibleBowl\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendConfirmationEmail implements ShouldQueue
{

    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  User  $user
     * @return void
     */
    public function handle(User $user)
    {
        // don't send email for third party registrations
        if ($user->providers()->count() == 0) {
            Mail::send(
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
