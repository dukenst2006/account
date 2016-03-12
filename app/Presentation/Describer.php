<?php namespace BibleBowl\Presentation;

use Carbon\Carbon;

/**
 * Responsible for further describing bits of information
 */
class Describer
{
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

        if (array_key_exists($grade, $grades)) {
            return $grades[$grade];
        }

        return $grade;
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

        if (array_key_exists($grade, $grades)) {
            return $grades[$grade];
        }

        return $grade;
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

        if (array_key_exists($size, $sizes)) {
            return $sizes[$size];
        }

        return $size;
    }

    /**
     * Display a date span
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return string
     */
    public static function dateSpan(Carbon $start, Carbon $end)
    {
        // Jul 11-15, 2015
        if ($start->format('mY') == $end->format('mY')) {
            return $start->format('M j - '.$end->format('j').', Y');
        } else {

            // Jun 28 - Jul 4, 2015
            if ($start->format('Y') == $end->format('Y')) {
                return $start->format('M j - ').$end->format('M j, Y');
            }
        }

        // Dec 28 2014 - Jan 2, 2015
        return $end->format('M j, Y').' - '.$end->format('M j, Y');
    }
}
