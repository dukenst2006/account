<?php namespace Lib;

class SeasonRegistrationHelper
{
    public static function dashboardRegistrationLink($playerName) {
        return '//a[contains(text(),"'.$playerName.'")]]/td[1]';
    }
}