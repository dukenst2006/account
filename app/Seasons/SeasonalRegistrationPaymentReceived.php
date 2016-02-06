<?php

namespace BibleBowl\Seasons;

use BibleBowl\Season;
use BibleBowl\Shop\PostPurchaseEvent;

class SeasonalRegistrationPaymentReceived extends PostPurchaseEvent
{
    const EVENT = 'seasonal.registration';

    /** @var Registration */
    protected $registration;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        parent::setEvent(self::EVENT);
    }

    /**
     * @param Registration $registration
     */
    public function setRegistration(Registration $registration)
    {
        parent::setEventData($registration);
    }

    /**
     * @return Registration
     */
    public function registration()
    {
        return app(Registration::class, [$this->eventData()]);
    }

    /**
     * Success message to display to the user after this
     * was purchased
     *
     * @return string
     */
    public function successMessage()
    {
        return 'Your player(s) have been registered!';
    }

    /**
     * Fire the event
     *
     * @return void
     */
    public function fire()
    {
        event($this->event(), [
            Season::current()->first(),
            $this->registration()
        ]);
    }
}