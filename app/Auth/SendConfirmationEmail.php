<?php namespace BibleBowl\Auth;

use Mail;
use BibleBowl\User;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class SendConfirmationEmail implements ShouldBeQueued {

	use InteractsWithQueue;

	/**
	 * Handle the event.
	 *
	 * @param  User  $user
	 * @return void
	 */
	public function handle(User $user)
	{
		Mail::send('emails.email-confirmation',
			[
				'user' => $user
			],
			function(Message $message) use ($user)
			{
				$message->to($user->email)->subject('Bible Bowl Account Email Confirmation');
			});
	}

}
