<?php

namespace BibleBowl\Competition\Tournaments\Quizmasters;

use BibleBowl\Competition\Tournaments;
use BibleBowl\Receipt;
use BibleBowl\Shop\PostPurchaseEvent;
use BibleBowl\TournamentQuizmaster;
use Illuminate\Http\Response;

class RegistrationPaymentReceived extends PostPurchaseEvent
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
    public function fire(Receipt $receipt = null)
    {
        $quizmaster = $this->tournamentQuizmaster();

        if ($receipt != null) {
            $quizmaster->update([
                'receipt_id' => $receipt->id,
            ]);
        }

        $quizmaster->notify(new RegistrationConfirmation());

        event($this->event(), [
            $this->tournamentQuizmaster(),
        ]);
    }
}
