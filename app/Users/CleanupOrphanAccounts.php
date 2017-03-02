<?php

namespace App\Users;

use App\User;
use App\Users\Notifications\AccountDeleted;
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
        $orphanUsers = User::where('created_at', '<', $orphanedFor)->requiresSetup()->get();

        foreach ($orphanUsers as $user) {
            // if registered with social media, delete those associations first
            $user->providers()->delete();

            $user->delete();

            $user->notify(new AccountDeleted());
        }
    }
}
