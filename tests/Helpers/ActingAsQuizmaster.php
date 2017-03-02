<?php

namespace Helpers;

use App\User;
use DatabaseSeeder;

trait ActingAsQuizmaster
{
    /** @var User */
    private $quizmaster;

    public function setupAsQuizmaster()
    {
        $this->quizmaster = User::where('email', DatabaseSeeder::QUIZMASTER_EMAIL)->first();

        $this->actingAs($this->quizmaster);
    }

    public function quizmaster()
    {
        return $this->quizmaster;
    }
}
