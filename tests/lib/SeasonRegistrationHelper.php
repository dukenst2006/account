<?php namespace Lib;

class SeasonRegistrationHelper
{

    public static function dashboardRegistrationLink($playerName) {
        return self::playerRow($playerName).'/td/a[contains(text(), "Register")]';
    }

    public static function selectThisGroupLink($groupName) {
        return self::groupRow($groupName).'/td/a[contains(text(), "Select this group")]';
    }

    public static function joinGroupLink($groupName) {
        return self::groupRow($groupName).'/td/a[contains(text(), "Join this group")]';
    }

    /**
     * XPath for finding a group's row based on name
     */
    private static function groupRow($groupName)
    {
        return '//tr[td/strong[contains(text(), "'.$groupName.'")]]';
    }

    /**
     * XPath for finding a player's row based on name
     */
    public static function playerRow($playerName)
    {
        return '//tr[td[contains(text(), "'.$playerName.'")]]';
    }
}