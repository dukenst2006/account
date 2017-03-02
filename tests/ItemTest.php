<?php

use App\EventType;
use App\Item;
use App\Player;
use App\Program;
use App\Spectator;
use App\Team;
use App\TournamentQuizmaster;

class ItemTest extends TestCase
{
    /** @test */
    public function generatesSeasonalRegistrationDescription()
    {
        foreach (Program::all() as $program) {
            $item = new Item([
                'sku' => $program->sku,
            ]);
            $this->assertEquals($program->name.' Seasonal Registration', $item->name());
        }
    }

    /** @test */
    public function generatesTournamentQuizmasterDescriptions()
    {
        $item = new Item([
            'sku' => TournamentQuizmaster::REGISTRATION_SKU,
        ]);
        $this->assertEquals('Quizmaster Tournament Registration', $item->name());

        $item = new Item([
            'sku' => TournamentQuizmaster::REGISTRATION_SKU.'_EARLY_BIRD',
        ]);
        $this->assertEquals('Quizmaster Tournament Registration (Early Bird)', $item->name());
    }

    /** @test */
    public function generatesTournamentSpectatorAdultDescriptions()
    {
        $item = new Item([
            'sku' => Spectator::REGISTRATION_ADULT_SKU,
        ]);
        $this->assertEquals('Adult Tournament Registration', $item->name());

        $item = new Item([
            'sku' => Spectator::REGISTRATION_ADULT_SKU.'_EARLY_BIRD',
        ]);
        $this->assertEquals('Adult Tournament Registration (Early Bird)', $item->name());
    }

    /** @test */
    public function generatesTournamentSpectatorFamilyDescriptions()
    {
        $item = new Item([
            'sku' => Spectator::REGISTRATION_FAMILY_SKU,
        ]);
        $this->assertEquals('Family Tournament Registration', $item->name());

        $item = new Item([
            'sku' => Spectator::REGISTRATION_FAMILY_SKU.'_EARLY_BIRD',
        ]);
        $this->assertEquals('Family Tournament Registration (Early Bird)', $item->name());
    }

    /** @test */
    public function generatesTournamentTeamDescriptions()
    {
        $item = new Item([
            'sku' => Team::REGISTRATION_SKU,
        ]);
        $this->assertEquals('Team Tournament Registration', $item->name());

        $item = new Item([
            'sku' => Team::REGISTRATION_SKU.'_EARLY_BIRD',
        ]);
        $this->assertEquals('Team Tournament Registration (Early Bird)', $item->name());
    }

    /** @test */
    public function generatesTournamentPlayerDescriptions()
    {
        $item = new Item([
            'sku' => Player::REGISTRATION_SKU,
        ]);
        $this->assertEquals('Player Tournament Registration', $item->name());

        $item = new Item([
            'sku' => Player::REGISTRATION_SKU.'_EARLY_BIRD',
        ]);
        $this->assertEquals('Player Tournament Registration (Early Bird)', $item->name());
    }

    /** @test */
    public function generatesEventDescriptions()
    {
        $eventType = EventType::find(1);
        $item = new Item([
            'sku' => 'TOURNAMENT_REG_EVENT_'.$eventType->id.'',
        ]);
        $this->assertEquals($eventType->participantType->name.' Round Robin Registration', $item->name());
    }
}
