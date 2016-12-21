<?php

use BibleBowl\Event;
use BibleBowl\Player;
use BibleBowl\Season;

class TournamentEventsTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Helpers\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
    }

    /**
     * @test
     */
    public function canCreateEvent()
    {
        $price = rand(10, 100);
        $this
            ->visit('/admin/tournaments/1')
            ->click('Add Event')
            ->select(3, 'event_type_id')
            ->type($price, 'price_per_participant')
            ->press('Save')
            ->seePageIs('/admin/tournaments/1')
            ->see('$'.$price);
    }

    /**
     * @test
     */
    public function canEditEvent()
    {
        $event = Event::orderBy('created_at', 'DESC')->first();
        $newPrice = rand(1, 100);
        $this
            ->visit('/admin/tournaments/1')
            ->click('#edit-'.$event->id)
            ->type($newPrice, 'price_per_participant')
            ->press('Save')
            ->see($newPrice)
            ->see($event->tournament->name);

        // Cleaning up
        $event->update([
            'price_per_participant' => $event->price_per_participant,
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

    /**
     * @test
     */
    public function canExportEventParticipants()
    {
        $currentSeason = Season::current()->firstOrFail();
        $event = Event::findOrFail(3); // Quote Bee
        $player = Player::whereHas('seasons', function ($q) use ($currentSeason) {
            $q->where('seasons.id', $currentSeason->id);
        })->firstOrFail();

        // Associate a player with this event
        DB::insert('INSERT INTO biblebowl_account.event_player(event_id, player_id, receipt_id) VALUES('.$event->id.', '.$player->id.', 1000)');

        ob_start();
        $this
            ->visit('/admin/tournaments/'.$event->tournament->id.'/events/'.$event->id.'/participants/export/csv')
            ->assertResponseOk();

        $csvContents = ob_get_contents();
        ob_end_clean();

        $this->assertContains($player->first_name, $csvContents);
        $this->assertContains($player->last_name, $csvContents);
        $this->assertContains('Mount Pleasant', $csvContents);
    }
}
