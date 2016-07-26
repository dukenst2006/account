<?php namespace BibleBowl\Users\Auth;

use BibleBowl\User;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;

class EmailConfirmationReminder extends Command
{

    const COMMAND = 'biblebowl:remind-confirmation-emails';

    private $reminderAfterDays = [
        '2',
        '10'
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = self::COMMAND;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Begin the next season if the current date indicates it's time";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        foreach ($this->reminderAfterDays as $days) {
            $day = Carbon::now()->subDays($days);

            /** @var User[] $unconfirmedUsers */
            $unconfirmedUsers = User::where('status', User::STATUS_UNCONFIRMED)
                ->whereBetween($day->startOfDay(), $day->endOfDay())
                ->get();

            /** @var SendConfirmationEmail $confirmationMailer */
            $confirmationMailer = app(SendConfirmationEmail::class);

            foreach ($unconfirmedUsers as $user) {
                $confirmationMailer->handle($user);
            }
        }
    }
}
