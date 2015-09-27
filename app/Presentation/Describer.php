<?php namespace BibleBowl\Presentation;

use BibleBowl\Group;
use BibleBowl\User;

/**
 * Responsible for further describing bits of information
 */
class Describer
{
    private static $grades = [];

    public static function describeGrade($grade)
    {
        $grades = [
            '3' => '3rd',
            '4' => '4th',
            '5' => '5th',
            '6' => '6th',
            '7' => '7th',
            '8' => '8th',
            '9' => '9th - Freshman',
            '10' => '10th - Sophomore',
            '11' => '11th - Junior',
            '12' => '12th - Senior'
        ];

        return $grades[$grade];
    }

    public static function describeGender($gender)
    {
        $genders = [
            'M' => 'Male',
            'F' => 'Female'
        ];

        return $genders[$gender];
    }

    public static function describeGradeShort($grade)
    {
        $grades = [
            '3' => '3rd',
            '4' => '4th',
            '5' => '5th',
            '6' => '6th',
            '7' => '7th',
            '8' => '8th',
            '9' => '9th',
            '10' => '10th',
            '11' => '11th',
            '12' => '12th'
        ];

        return $grades[$grade];
    }

    public static function describeShirtSize($size)
    {
        $sizes = [
            'YS' => 'YS - Youth Small',
            'YM' => 'YM - Youth Medium',
            'YL' => 'YL - Youth Large',
            'S' => 'S - Small',
            'M' => 'M - Medium',
            'L' => 'L - Large',
            'XL' => 'XL - X-Large',
            'XXL' => 'XXL - XX-Large'
        ];
        return $sizes[$size];
    }

}