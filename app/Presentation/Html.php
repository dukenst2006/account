<?php namespace BibleBowl\Presentation;

use Illuminate\Html\HtmlBuilder;

class Html extends HtmlBuilder
{
    const GENDER_MALE = 'M';
    const GENDER_FEMALE = 'F';

    public function genderIcon($gender)
    {
        if ($gender == self::GENDER_MALE) {
            return '<i class="fa fa-male"></i>';
        } elseif ($gender == self::GENDER_FEMALE) {
            return '<i class="fa fa-female"></i>';
        }
    }

}