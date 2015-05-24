<?php namespace BibleBowl\Support;

/**
 * Remove characters that don't match the given type
 */
class Scrubber
{

    /**
     * Remove all non alpha characters
     *
     * @param $string
     *
     * @return string
     */
    public function alpha($string)
    {
        return trim(preg_replace("/[^A-Za-z ]/", '', $string));
    }

    /**
     * Remove all non alphanumeric characters
     *
     * @param $string
     *
     * @return string
     */
    public function alphaNumeric($string)
    {
        return trim(preg_replace("/[^A-Za-z0-9 ]/", '', $string));
    }

    /**
     * Remove all characters that may dirty up an email address
     *
     * @param $string
     *
     * @return string
     *
     * @link http://stackoverflow.com/a/2049510/197606
     */
    public function email($string)
    {
        $specialCharacters = preg_quote("!#$%&@'*+-./=?^_`{|}~", '/');
        return trim(preg_replace("/[^A-Za-z0-9".$specialCharacters." ]/", '', $string));
    }

    /**
     * Remove all characters except digits, commas and periods
     *
     * @param $string
     *
     * @return string
     */
    public function numeric($string)
    {
        return preg_replace("/[^0-9,.]/", "", $string);
    }

    /**
     * Remove all characters except digits, commas and periods
     *
     * @param $string
     *
     * @return string
     */
    public function integer($string)
    {
        return preg_replace("/[^0-9]/", "", $string);
    }

    /**
     * Converts a phone number that may have dashes or other invalid
     * characters to a single phone number as digits
     *
     * @param $string the phone number to normalize
     *
     * @return string
     */
    public function phone($string)
    {
        $phone_number =  $this->integer($string);
        if (strlen($phone_number) == 11) {
            $phone_number = substr($phone_number, 1);
        }
        return $phone_number;
    }
}