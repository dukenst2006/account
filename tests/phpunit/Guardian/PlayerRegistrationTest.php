<?php

use BibleBowl\Season;
use Illuminate\Database\Eloquent\Builder;

class PlayerRegistrationTest extends TestCase
{

    use \Lib\Roles\ActingAsGuardian;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsGuardian();
    }

    /**
     * @test
     */
    public function editExistingRegistration()
    {
        $player = $this->guardian()->players()->whereHas('seasons', function (Builder $q) {
            $q->where('seasons.id', Season::first()->id);
        })->first();

        $this
            ->visit('/dashboard')
            ->click('#edit-registration-'.$player->id)
            ->select(rand(3, 12), 'grade')
            ->select(array_rand(['S', 'M', 'L', 'XL']), 'shirt_size')
            ->submitForm('Save')
            ->see('Registration has been updated');
    }

}