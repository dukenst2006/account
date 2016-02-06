<?php

namespace BibleBowl\Seasons;

use BibleBowl\Season;
use BibleBowl\Shop\PostPurchaseEvent;

class SeasonalRegistrationPaymentReceived extends PostPurchaseEvent
{
    const EVENT = 'seasonal.registration';

    /** @var SeasonalRegistration */
    protected $registration;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        parent::setEvent(self::EVENT);
    }

    /**
     * @param SeasonalRegistration $registration
     */
    public function setRegistration(SeasonalRegistration $registration)
    {
        parent::setEventData($registration);
    }

    /**
     * @return SeasonalRegistration
     */
    public function registration()
    {
        return app(SeasonalRegistration::class, [$this->eventData()]);
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