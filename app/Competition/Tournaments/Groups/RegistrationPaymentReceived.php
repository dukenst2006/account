<?php

namespace BibleBowl\Competition\Tournaments\Groups;

use BibleBowl\Competition\Tournaments;
use BibleBowl\Competition\Tournaments\Quizmasters\RegistrationConfirmation as QuizmasterRegistrationConfirmation;
use BibleBowl\Competition\Tournaments\Spectators\RegistrationConfirmation as SpectatorRegistrationConfirmation;
use BibleBowl\Event;
use BibleBowl\Receipt;
use BibleBowl\Shop\PostPurchaseEvent;
use BibleBowl\Spectator;
use BibleBowl\Team;
use BibleBowl\TournamentQuizmaster;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Response;

class RegistrationPaymentReceived extends PostPurchaseEvent
{
    const EVENT = 'tournament.registration.group.payment';

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        parent::setEvent(self::EVENT);
    }

    public function setGroupRegistration(Registration $groupRegistration)
    {
        parent::setEventData(collect($groupRegistration->toArray()));
    }

    public function groupRegistration() : Registration
    {
        return new Registration($this->eventData());
    }

    /**
     * The step to execute immediately after payment is accepted.
     *
     * @return Response
     */
    public function successStep()
    {
        $tournament = $this->groupRegistration()->tournament();

        return redirect('/tournaments/'.$tournament->slug.'/group')->withFlashSuccess('Your registration is complete');
    }

    /**
     * Fire the event.
     *
     * @return void
     */
    public function fire(Receipt $receipt)
    {
        $registration = $this->groupRegistration();

        // ----------------------------------
        // associate receipts with players, quizmasters, events and such
        // included as part of the registration
        //
        Team::whereIn('id', $registration->teamIds())->update([
            'receipt_id' => $receipt->id,
        ]);

        if (count($registration->playerIds()) > 0) {
            $insertData = [];
            foreach ($registration->playerIds() as $playerId) {
                $insertData[] = [
                    'tournament_id' => $registration->tournament()->id,
                    'player_id'     => $playerId,
                    'receipt_id'    => $receipt->id,
                    'updated_at'    => Carbon::now()->toDateTimeString(),
                    'created_at'    => Carbon::now()->toDateTimeString(),
                ];
            }
            DB::table('tournament_players')->insert($insertData);
        }

        TournamentQuizmaster::whereIn('id', $registration->quizmasterIds())->update([
            'receipt_id' => $receipt->id,
        ]);

        $spectatorIds = array_merge($registration->familyIds(), $registration->adultIds());
        Spectator::whereIn('id', $spectatorIds)->update([
            'receipt_id' => $receipt->id,
        ]);

        foreach ($registration->eventParticipants() as $eventId => $participantIds) {
            $event = Event::find($eventId);
            foreach ($participantIds as $participantId) {
                $event->players()->updateExistingPivot($participantId, [
                    'receipt_id' => $receipt->id,
                ]);
            }
        }
        // ----------------------------------

        // notify quizmasters, request quizzing preferences if necessary
        $quizmasters = TournamentQuizmaster::whereIn('id', $registration->quizmasterIds())->get();
        foreach ($quizmasters as $quizmaster) {
            $quizmaster->notify(new QuizmasterRegistrationConfirmation());
        }

        // notify quizmasters, request quizzing preferences if necessary
        $spectators = Spectator::whereIn('id', $registration->spectatorIds())->get();
        foreach ($spectators as $spectator) {
            $spectator->notify(new SpectatorRegistrationConfirmation());
        }

        $receipt->user->notify(new RegistrationPaymentConfirmation($registration));

        event($this->event(), [
            $registration,
        ]);
    }
}
