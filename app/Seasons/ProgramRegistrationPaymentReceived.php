<?php

namespace BibleBowl\Seasons;

use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\Shop\PostPurchaseEvent;
use Illuminate\Support\Collection;

class ProgramRegistrationPaymentReceived extends PostPurchaseEvent
{
    const EVENT = 'seasonal.registration.payment';

    /** @var SeasonalRegistration */
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
     * Success message to display to the user after this
     * was purchased
     *
     * @return string
     */
    public function successMessage()
    {
        return 'Payment has been received!';
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