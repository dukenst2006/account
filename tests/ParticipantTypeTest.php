<?php

use BibleBowl\ParticipantType;
use BibleBowl\Player;
use BibleBowl\Spectator;
use BibleBowl\Team;
use BibleBowl\Tournament;
use BibleBowl\TournamentQuizmaster;

class ParticipantTypeTest extends TestCase
{
    /** @test */
    public function providesEarlyBirdSkus()
    {
        $tournament = Mockery::mock(Tournament::class);
        $tournament->shouldReceive('hasEarlyBirdRegistrationFee')->andReturnTrue();

        $this->assertEquals(Team::REGISTRATION_SKU.'_EARLY_BIRD', ParticipantType::sku($tournament, ParticipantType::TEAM));
        $this->assertEquals(Player::REGISTRATION_SKU.'_EARLY_BIRD', ParticipantType::sku($tournament, ParticipantType::PLAYER));
        $this->assertEquals(TournamentQuizmaster::REGISTRATION_SKU.'_EARLY_BIRD', ParticipantType::sku($tournament, ParticipantType::QUIZMASTER));
        $this->assertEquals(Spectator::REGISTRATION_FAMILY_SKU.'_EARLY_BIRD', ParticipantType::sku($tournament, ParticipantType::FAMILY));
        $this->assertEquals(Spectator::REGISTRATION_ADULT_SKU.'_EARLY_BIRD', ParticipantType::sku($tournament, ParticipantType::ADULT));
    }

    /** @test */
    public function providesRegularSkus()
    {
        $tournament = Mockery::mock(Tournament::class);
        $tournament->shouldReceive('hasEarlyBirdRegistrationFee')->andReturnFalse();

        $this->assertEquals(Team::REGISTRATION_SKU, ParticipantType::sku($tournament, ParticipantType::TEAM));
        $this->assertEquals(Player::REGISTRATION_SKU, ParticipantType::sku($tournament, ParticipantType::PLAYER));
        $this->assertEquals(TournamentQuizmaster::REGISTRATION_SKU, ParticipantType::sku($tournament, ParticipantType::QUIZMASTER));
        $this->assertEquals(Spectator::REGISTRATION_FAMILY_SKU, ParticipantType::sku($tournament, ParticipantType::FAMILY));
        $this->assertEquals(Spectator::REGISTRATION_ADULT_SKU, ParticipantType::sku($tournament, ParticipantType::ADULT));
    }
}
