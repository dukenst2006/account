<?php

use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\User;
use BibleBowl\Users\Auth\SessionManager;
use Carbon\Carbon;

class PlayerTest extends TestCase
{
    protected $firstName = 'Lucy';
    protected $lastName = 'Tharn';

    use \Helpers\ActingAsGuardian;
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsGuardian();
        $this->withSession([
            SessionManager::SEASON  => $this->season->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function canCreatePlayers()
    {
        $this
            ->visit('/player/create')
            ->type($this->lastName, 'last_name')
            ->type('05/14/2001', 'birthday')
            ->press('Save')
            ->see('The first name field is required.')
            ->type($this->firstName, 'first_name')
            ->press('Save')
            ->see($this->firstName.' '.$this->lastName.' has been added');
    }

    /**
     * @test
     */
    public function cantCreateSamePlayerTwice()
    {
        $player = $this->guardian->players()->first();
        $this
            ->visit('/player/create')
            ->type($player->last_name, 'last_name')
            ->type('05/14/2001', 'birthday')
            ->type($player->first_name, 'first_name')
            ->press('Save')
            ->see("You've already added this player");
    }

    /**
     * @test
     */
    public function canEditPlayers()
    {
        $player = $this->guardian->players()->first();
        $newName = time();
        $this
            ->visit('/dashboard')
            ->click('#edit-child-'.$player->id)
            ->type($newName, 'first_name')
            ->press('Save')
            ->see('Your changes were saved')
            ->see($newName);
    }

    /**
     * @test
     */
    public function guardianCantAlwaysEditBirthday()
    {
        /** @var Player $player */
        $player = $this->guardian->players()->first();
        $player->birthday = Carbon::now()->format('m/d/Y');
        $this->assertTrue($player->seasons()->count() == 0);

        // admins can edit
        $user = Mockery::mock(User::class);
        $user->shouldReceive('isA')->andReturn(true);
        $this->assertTrue($player->isBirthdayEditable($user));

        $user = Mockery::mock(User::class);
        $user->shouldReceive('isA')->andReturn(false);

        // can't edit after a few months
        $player->created_at = Carbon::now()->subMonths(4)->subDays(2);
        $this->assertFalse($player->isBirthdayEditable($user));

        // can't edit after first season
        $player->created_at = Carbon::now();
        Season::current()->first()->players()->attach($player->id, [
            'grade'         => '11',
            'shirt_size'    => 'M',
            'group_id'      => 1,
        ]);
        $this->assertTrue($player->isBirthdayEditable($user));
    }
}
