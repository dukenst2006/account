<?php

namespace BibleBowl\Users;

use BibleBowl\User;
use BibleBowl\Users\Notifications\AccountDeleted;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupOrphanAccounts extends Command
{
    const COMMAND = 'biblebowl:cleanup-orphan-accounts';

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
    protected $description;

    protected $cleanupAfter;

    public function __construct()
    {
        $this->cleanupAfter = config('biblebowl.cleanup-orphan-accounts-after');

        $this->description = 'Cleanup accounts over '.$this->cleanupAfter.' days old';

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $orphanedFor = Carbon::now()->subDays($this->cleanupAfter);
        $orphanUsers = User::where('status', User::STATUS_UNCONFIRMED)->where('created_at', '>', $orphanedFor)->get();

        foreach ($orphanUsers as $user) {
            // if registered with social media, delete those associations first
            $user->providers()->delete();

            // unlink & remove primary address
            if ($user->primary_address_id != null) {
                $user->update([
                    'primary_address_id' => null,
                ]);
                $user->primaryAddress()->delete();
            }

            $user->delete();

            $user->notify(new AccountDeleted());
        }
    }
}
