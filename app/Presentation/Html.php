<?php namespace BibleBowl\Presentation;

use Illuminate\Html\HtmlBuilder;

class Html extends HtmlBuilder
{
    const GENDER_MALE = 'M';
    const GENDER_FEMALE = 'F';

    /**
     * Render a gender icon
     *
     * @param $gender
     *
     * @return string
     */
    public function genderIcon($gender)
    {
        if ($gender == self::GENDER_MALE) {
            return '<i class="fa fa-male"></i>';
        } elseif ($gender == self::GENDER_FEMALE) {
            return '<i class="fa fa-female"></i>';
        }
    }

    /**
     * Format a phone number
     *
     * @param $phone
     *
     * @return string
     */
    public function formatPhone($phone)
    {
        return preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $phone);
    }

}