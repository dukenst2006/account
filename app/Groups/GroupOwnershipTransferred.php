<?php

namespace App\Groups;

use App\Group;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupOwnershipTransferred extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var Group */
    protected $group;

    /** @var User */
    protected $previousOwner;

    /** @var User */
    protected $newOwner;

    public function __construct(Group $group, User $previousOwner, User $newOwner)
    {
        $this->group = $group;
        $this->previousOwner = $previousOwner;
        $this->newOwner = $newOwner;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->group->name.' Ownership Transfer')
            ->markdown('emails.group-ownership-transfer')
            ->with([
                'header'        => $this->group->name.' Ownership Transfer',
                'group'         => $this->group,
                'previousOwner' => $this->previousOwner,
                'newOwner'      => $this->newOwner,
            ]);
    }
}
