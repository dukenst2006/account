<?php

use BibleBowl\Tournament;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function cantRegisterWhenRegistrationClosed()
    {
        $tournament = Tournament::firstOrFail();
        $tournament->update([
            'registration_start'    => Carbon::now()->subDays(10)->format('m/d/Y'),
            'registration_end'      => Carbon::now()->subDays(1)->format('m/d/Y'),
        ]);

        $this
            ->visit('/tournaments/'.$tournament->slug)
            ->see('Online registration for this tournament is now closed');
    }

    /**
     * @test
     */
    public function suggestsOnsiteRegistration()
    {
        $tournament = Tournament::firstOrFail();
        $tournament->update([
            'registration_start'    => Carbon::now()->subDays(10)->format('m/d/Y'),
            'registration_end'      => Carbon::now()->subDays(1)->format('m/d/Y'),
        ]);

        $this
            ->visit('/tournaments/'.$tournament->slug)
            ->see('Quizmaster, Adult and Family registrations will be accepted onsite.');
    }
}
