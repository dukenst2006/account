<?php

namespace BibleBowl\Competition\Tournaments;


interface Quizmasterable
{
    public function id() : int;

    public function firstName() : string;

    public function setFirstName(string $firstName);

    public function lastName() : string;

    public function setLastName(string $lastName);

    public function email() : string;

    public function setEmail(string $email);
    
    
}