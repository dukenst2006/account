<?php

namespace BibleBowl\Groups;

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
        $html = trim($html);

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

    public function shouldUpdateSubscribers()
    {
        return
            $this->mailchimpEnabled() &&
            $this->mailchimpKey() != null &&
            $this->mailchimpListId() != null;
    }

    public function mailchimpEnabled()
    {
        return $this->get('mailchimpEnabled', false);
    }

    public function setMailchimpEnabled($value)
    {
        return $this->mailchimpEnabled = (bool) $value;
    }

    public function mailchimpKey()
    {
        return $this->get('mailchimpKey', false);
    }

    public function setMailchimpKey($value)
    {
        return $this->mailchimpKey = trim($value);
    }

    public function mailchimpListId()
    {
        return $this->get('mailchimpListId', null);
    }

    public function setMailchimpListId($value)
    {
        return $this->mailchimpListId = trim($value);
    }
}
