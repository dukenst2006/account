<?php

use BibleBowl\Tournament;
use Carbon\Carbon;
use BibleBowl\Competition\TournamentCreator;
use BibleBowl\Event;

class TournamentEventsTest extends TestCase
{

    use \Lib\Roles\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();;
    }

    /**
     * @test
     */
    public function canCreateEvent()
    {
        $price = rand(10, 100).'.'.rand(1,99);
        $this
            ->visit('/admin/tournaments/1')
            ->click('Add Event')
            ->check('event_type_id')
            ->type($price, 'price_per_participant')
            ->press('Save')
            ->seePageIs('/admin/tournaments/1')
            ->see($price);
    }

    /**
     * @test
     */
    public function canEditEvent()
    {
        $event = Event::orderBy('created_at', 'DESC')->first();
        $newPrice = rand(1,100);
        $this
            ->visit('/admin/tournaments/1')
            ->click('#edit-'.$event->id)
            ->type($newPrice, 'price_per_participant')
            ->press('Save')
            ->see($newPrice)
            ->see($event->tournament->name);

        # Cleaning up
        $event->update([
            'price_per_participant' => $event->price_per_participant
        ]);
    }

    /**
     * @test
     */
    public function canDeleteEvent()
    {
        $event = Event::orderBy('created_at', 'DESC')->first();
        $this
            ->visit('/admin/tournaments/'.$event->tournament->id)
            ->click('#delete-'.$event->id)
            ->see($event->name);
    }

}