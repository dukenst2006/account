<?php

namespace BibleBowl\Competition\Tournaments\Registration;

use BibleBowl\Receipt;
use BibleBowl\Shop\PostPurchaseEvent;
use BibleBowl\TournamentQuizmaster;
use Illuminate\Http\Response;

class QuizmasterRegistrationPaymentReceived extends PostPurchaseEvent
{
    const EVENT = 'tournament.registration.quizmaster.payment';

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        parent::setEvent(self::EVENT);
    }

    /**
     * @param $quizmasterRegistrationId
     */
    public function setTournamentQuizmaster(TournamentQuizmaster $tournamentQuizmaster)
    {
        parent::setEventData(collect($tournamentQuizmaster->id));
    }

    /**
     * @return TournamentQuizmaster
     */
    public function tournamentQuizmaster()
    {
        return TournamentQuizmaster::where('id', $this->eventData()[0])->first();
    }

    /**
     * The step to execute immediately after payment is accepted.
     *
     * @return Response
     */
    public function successStep()
    {
        $tournament = $this->tournamentQuizmaster()->tournament;

        return redirect('/tournaments/'.$tournament->slug)->withFlashSuccess('Your quizmaster registration is complete!');
    }

    /**
     * Fire the event.
     *
     * @return void
     */
    public function fire(Receipt $receipt)
    {
        $this->tournamentQuizmaster()->update([
            'receipt_id' => $receipt->id,
        ]);

        event($this->event(), [
            $this->quizmasterRegistration(),
        ]);
    }
}
