<?php

namespace BibleBowl\Competition;

use BibleBowl\EventType;
use Illuminate\Support\Fluent;

class Fees extends Fluent
{
    public function setSpectator($fee)
    {
        return $this->spectator = $fee;
    }

    public function spectator()
    {
        return $this->get('participant', null);
    }

    public function hasSpectatorFee()
    {
        return $this->spectator() !== null;
    }

    public function setParticipant($participantType, $fee)
    {
        return $this->participant = $fee;
    }

    public function participant()
    {
        return $this->get('participant');
    }

    public function hasParticipantFee()
    {
        return $this->participant() !== null;
    }
}