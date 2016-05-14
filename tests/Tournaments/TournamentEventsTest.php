<?php

use BibleBowl\Event;

class TournamentEventsTest extends TestCase
{

    use \Helpers\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
        ;
    }

    /**
     * @test
     */
    public function canCreateEvent()
    {
        $price = rand(10, 100).'.'.rand(1, 99);
        $this
            ->visit('/tournaments/1')
            ->click('Add Event')
            ->select(3, 'event_type_id')
            ->type($price, 'price_per_participant')
            ->press('Save')
            ->seePageIs('/tournaments/1')
            ->see($price);
    }

    /**
     * @test
     */
    public function canEditEvent()
    {
        $event = Event::orderBy('created_at', 'DESC')->first();
        $newPrice = rand(1, 100);
        $this
            ->visit('/tournaments/1')
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
            ->visit('/tournaments/'.$event->tournament->id)
            ->click('#delete-'.$event->id)
            ->see($event->name);
    }
}