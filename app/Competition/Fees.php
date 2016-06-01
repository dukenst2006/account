<?php

namespace BibleBowl\Competition;

use BibleBowl\ParticipantType;
use Illuminate\Support\Fluent;

class Fees extends Fluent
{
    public function setFee(ParticipantType $participantType, $fee)
    {
        return $this->{$participantType->id} = $fee;
    }

    public function fee(ParticipantType $participantType)
    {
        return $this->get($participantType->id, null);
    }

    public function hasFee(ParticipantType $participantType)
    {
        return $this->fee($participantType) !== null;
    }
}