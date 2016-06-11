<?php

namespace BibleBowl\Competition\Tournaments;

use Illuminate\Support\Fluent;

class Quizmaster extends Fluent implements Quizmasterable
{

    public function setFirstName(string $firstName)
    {
        $this->first_name = $firstName;
    }

    public function firstName() : string
    {
        return $this->first_name;
    }

    public function setLastName(string $lastName)
    {
        $this->last_name = $lastName;
    }

    public function lastName() : string
    {
        return $this->last_name;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function email() : string
    {
        return $this->email;
    }
}