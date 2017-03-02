<?php

namespace App\Groups;

use App\Group;
use App\Seasons\GroupRegistration;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use League\HTMLToMarkdown\HtmlConverter;

class NewRegistrationNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var User */
    protected $guardian;

    /** @var Group */
    protected $group;

    /** @var GroupRegistration */
    protected $registration;

    /** @var string */
    protected $contentBody;

    public function __construct(
        User $guardian,
        Group $group,
        GroupRegistration $registration,
        string $contentBody = null
    ) {
        $htmlConverter = new HtmlConverter();

        $this->guardian = $guardian;
        $this->group = $group;
        $this->registration = $registration;

        // the wisywig uses HTML, so convert to Markdown
        if ($contentBody != null) {
            $this->contentBody = $htmlConverter->convert($contentBody);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Since this email is queued, we need to get pivot data now and include it with $players
        // because once it actually gets processed $players won't be an object making it more
        // difficult to fetch this data
        $players = [];
        $grades = [];
        $shirtSizes = [];
        foreach ($this->registration->players($this->group->program) as $player) {
            $player->full_name = $player->full_name;
            $grades[$player->id] = $this->registration->grade($player->id);
            $shirtSizes[$player->id] = $this->registration->shirtSize($player->id);
            $players[] = $player;
        }

        // setting this value so that it's available in the toArray() so queued mail can use it
        $this->guardian->full_name = $this->guardian->full_name;

        return $this->subject('New '.$this->group->name.' Registration'.(count($players) > 1 ? 's' : ''))
            ->markdown('emails.group-welcome-email')
            ->with([
                'group'         => $this->group,
                'guardian'      => $this->guardian,
                'players'       => $players,
                'grades'        => $grades,
                'shirtSizes'    => $shirtSizes,
                'hasEmailBody'  => false,
            ]);
    }
}
