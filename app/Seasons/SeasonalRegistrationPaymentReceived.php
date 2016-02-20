<?php

namespace BibleBowl\Seasons;

use Session;
use BibleBowl\Shop\PostPurchaseEvent;

class SeasonalRegistrationPaymentReceived extends PostPurchaseEvent
{
    const EVENT = 'seasonal.registration';

    /** @var GroupRegistration */
    protected $registration;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        parent::setEvent(self::EVENT);
    }

    /**
     * @param GroupRegistration $registration
     */
    public function setRegistration(GroupRegistration $registration)
    {
        parent::setEventData($registration);
    }

    /**
     * @return GroupRegistration
     */
    public function registration()
    {
        return app(GroupRegistration::class, [$this->eventData()]);
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
            Session::season(),
            $this->registration()
        ]);
    }
}