<?php

namespace BibleBowl\Competition\Tournaments\Registration;

use BibleBowl\Player;
use BibleBowl\Receipt;
use BibleBowl\Season;
use BibleBowl\Shop\PostPurchaseEvent;
use BibleBowl\Spectator;
use BibleBowl\TournamentQuizmaster;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SpectatorRegistrationPaymentReceived extends PostPurchaseEvent
{
    const EVENT = 'tournament.registration.spectator.payment';

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        parent::setEvent(self::EVENT);
    }

    /**
     * @param $spectatorId
     */
    public function setSpectator(Spectator $spectatorId)
    {
        parent::setEventData(collect($spectatorId->id));
    }

    /**
     * @return Spectator
     */
    public function spectator()
    {
        return Spectator::where('id', $this->eventData()[0])->first();
    }

    /**
     * The step to execute immediately after payment is accepted
     *
     * @return Response
     */
    public function successStep()
    {
        $tournament = $this->spectator()->tournament;
        return redirect('/tournaments/'.$tournament->slug)->withFlashSuccess('Your registration is complete!');
    }

    /**
     * Fire the event
     *
     * @return void
     */
    public function fire(Receipt $receipt)
    {
        $this->spectator()->update([
            'receipt_id' => $receipt->id
        ]);

        event($this->event(), [
            $this->quizmasterRegistration()
        ]);
    }
}
