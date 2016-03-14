<?php

namespace BibleBowl\Seasons;

use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\Shop\PostPurchaseEvent;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class ProgramRegistrationPaymentReceived extends PostPurchaseEvent
{
    const EVENT = 'seasonal.registration.payment';

    /** @var GroupRegistration */
    protected $registration;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        parent::setEvent(self::EVENT);
    }

    /**
     * @param Collection $playerIds
     */
    public function setPlayers(Collection $playerIds)
    {
        parent::setEventData($playerIds);
    }

    /**
     * @return Player[]
     */
    public function players()
    {
        return Player::whereIn('id', $this->eventData())->get();
    }

    /**
     * The step to execute immediately after payment is accepted
     *
     * @return Response
     */
    public function successStep()
    {
        return redirect('/dashboard')->withFlashSuccess('Payment has been received!');
    }

    /**
     * Fire the event
     *
     * @return void
     */
    public function fire()
    {
        event($this->event(), [
            $this->players()
        ]);
    }
}