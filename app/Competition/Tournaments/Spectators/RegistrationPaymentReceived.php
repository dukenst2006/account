<?php

namespace App\Competition\Tournaments\Spectators;

use App\Receipt;
use App\Shop\PostPurchaseEvent;
use App\Spectator;
use Illuminate\Http\Response;

class RegistrationPaymentReceived extends PostPurchaseEvent
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
     * The step to execute immediately after payment is accepted.
     *
     * @return Response
     */
    public function successStep()
    {
        $tournament = $this->spectator()->tournament;

        return redirect('/tournaments/'.$tournament->slug)->withFlashSuccess('Your registration is complete!');
    }

    /**
     * Fire the event.
     *
     * @return void
     */
    public function fire(Receipt $receipt)
    {
        $this->spectator()->update([
            'receipt_id' => $receipt->id,
        ]);

        $receipt->update([
            'tournament_id' => $this->spectator()->tournament_id,
        ]);

        event($this->event(), [
            $this->quizmasterRegistration(),
        ]);
    }
}
