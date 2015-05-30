<?php namespace BibleBowl\Presentation;

use Illuminate\Html\HtmlBuilder;

class Html extends HtmlBuilder
{

    public function genderIcon($gender)
    {
        if ($gender == 'M') {
            return '<i class="fa fa-male"></i>';
        } elseif ($gender == 'F') {
            return '<i class="fa fa-female"></i>';
        }
    }

}