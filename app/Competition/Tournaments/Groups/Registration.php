<?php

namespace BibleBowl\Competition\Tournaments\Groups;

use BibleBowl\Cart;
use BibleBowl\Event;
use BibleBowl\ParticipantType;
use BibleBowl\Tournament;
use Illuminate\Support\Fluent;

class Registration extends Fluent
{
    protected $attributes = [
        'tournamentId'      => null,
        'teamIds'           => [],
        'quizmasterIds'     => [],
        'spectatorIds'      => [],
        'playerIds'         => [],
        'eventParticipants' => [],
    ];

    public function setTournament(Tournament $tournament)
    {
        $this->attributes['tournamentId'] = $tournament->id;
    }

    public function tournament() : Tournament
    {
        return Tournament::find($this->attributes['tournamentId']);
    }

    public function setTeamIds(array $teamIds)
    {
        $this->attributes['teamIds'] = $teamIds;
    }

    public function teamIds() : array
    {
        return $this->attributes['teamIds'];
    }

    public function setQuizmasterIds(array $quizmasterIds)
    {
        $this->attributes['quizmasterIds'] = $quizmasterIds;
    }

    public function quizmasterIds() : array
    {
        return $this->attributes['quizmasterIds'];
    }

    public function setAdultIds(array $adultIds)
    {
        $this->attributes['adultIds'] = $adultIds;
    }

    public function adultIds() : array
    {
        return $this->attributes['adultIds'];
    }

    public function setFamilyIds(array $familyIds)
    {
        $this->attributes['familyIds'] = $familyIds;
    }

    public function familyIds() : array
    {
        return $this->attributes['familyIds'];
    }

    public function spectatorIds() : array
    {
        return array_merge($this->familyIds(), $this->adultIds());
    }

    public function setPlayerIds(array $playerIds)
    {
        $this->attributes['playerIds'] = $playerIds;
    }

    public function playerIds() : array
    {
        return $this->attributes['playerIds'];
    }

    public function addEventParticipants(int $eventId, array $eventParticipants)
    {
        $this->attributes['eventParticipants'][$eventId] = $eventParticipants;
    }

    public function eventParticipants() : array
    {
        return $this->attributes['eventParticipants'];
    }

    /**
     * Take the group registration components and populate the shopping cart.
     */
    public function populateCart(Cart $cart) : Cart
    {
        $tournament = $this->tournament();

        $teamCount = count($this->teamIds());
        if ($teamCount > 0) {
            $cart->add(
                ParticipantType::sku($tournament, ParticipantType::TEAM),
                $tournament->fee(ParticipantType::TEAM) * $teamCount,
                $teamCount
            );
        }

        $playerCount = count($this->playerIds());
        if ($playerCount > 0) {
            $cart->add(
                ParticipantType::sku($tournament, ParticipantType::PLAYER),
                $tournament->fee(ParticipantType::PLAYER) * $playerCount,
                $playerCount
            );
        }

        $quizmasterCount = count($this->quizmasterIds());
        if ($quizmasterCount > 0) {
            $cart->add(
                ParticipantType::sku($tournament, ParticipantType::QUIZMASTER),
                $tournament->fee(ParticipantType::QUIZMASTER) * $quizmasterCount,
                $quizmasterCount
            );
        }

        $adultCount = count($this->adultIds());
        if ($adultCount > 0) {
            $cart->add(
                ParticipantType::sku($tournament, ParticipantType::ADULT),
                $tournament->fee(ParticipantType::ADULT) * $adultCount,
                $adultCount
            );
        }

        $familyCount = count($this->familyIds());
        if ($familyCount > 0) {
            $cart->add(
                ParticipantType::sku($tournament, ParticipantType::FAMILY),
                $tournament->fee(ParticipantType::FAMILY) * $familyCount,
                $familyCount
            );
        }

        foreach ($this->eventParticipants() as $eventId => $participantIds) {
            $event = Event::find($eventId);
            $participantCount = count($participantIds);
            $cart->add(
                $event->sku(),
                $event->price_per_participant * $participantCount,
                $participantCount
            );
        }

        return $cart;
    }
}
