<?php namespace BibleBowl\Groups;

use Illuminate\Support\Fluent;

class Settings extends Fluent
{
    public function __construct($attributes = [])
    {
        if ($attributes !== null) {
            parent::__construct($attributes);
        }
    }

    public function registrationEmailContents()
    {
        return $this->get('regEmailContents', '');
    }

    public function setRegistrationEmailContents($html)
    {
        // exclude any leftover HTML
        if (strlen(strip_tags($html)) == 0) {
            $html = '';
        }

        $this->regEmailContents = $html;
    }

    public function hasRegistrationEmailContents()
    {
        return strlen($this->registrationEmailContents()) > 0;
    }
}
