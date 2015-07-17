<?php namespace BibleBowl\Presentation;

use Auth;
use BibleBowl\Group;
use BibleBowl\User;
use Carbon\Carbon;
use Illuminate\Html\FormBuilder;

class Form extends FormBuilder
{

    /**
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectState($name, $selected = null, $options = array(), $optional = false)
    {
        $list = [
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming'
        ];

        if ($optional) {
            array_unshift($list, 'Select One...');
        }

        return $this->select($name, $list, $selected, $options);
    }

    /**
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectShirtSize($name, $selected = null, $options = array(), $optional = false)
    {
        $list = [
            'Youth' => [
                'YS' => 'YS - Small',
                'YM' => 'YM - Medium',
                'YL' => 'YL - Large',
            ],
            'Adult' => [
                'S' => 'S - Small',
                'M' => 'M - Medium',
                'L' => 'L - Large',
                'XL' => 'XL - X-Large',
                'XXL' => 'XXL - XX-Large',
            ],
        ];

        if ($optional) {
            array_unshift($list, 'Select One...');
        }

        return $this->select($name, $list, $selected, $options);
    }

    /**
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectGroupType($name, $selected = null, $options = array(), $optional = false)
    {
        $list = [
            Group::TYPE_BEGINNER => 'Beginner (3rd-5th grade)',
            Group::TYPE_TEEN => 'Teen (6th-12th grade)'
        ];

        if ($optional) {
            array_unshift($list, 'Select One...');
        }

        return $this->select($name, $list, $selected, $options);
    }

    /**
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectGrade($name, $selected = null, $options = array(), $optional = false)
    {
        $list = [
            'Elementary School' => [
                '3' => '3rd',
                '4' => '4th',
                '5' => '5th'
            ],
            'Middle School' => [
                '6' => '6th',
                '7' => '7th',
                '8' => '8th'
            ],
            'High School' => [
                '9' => '9th - Freshman',
                '10' => '10th - Sophomore',
                '11' => '11th - Junior',
                '12' => '12th - Senior'
            ]
        ];

        if ($optional) {
            array_unshift($list, 'Select One...');
        }

        return $this->select($name, $list, $selected, $options);
    }

    /**
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectAddress($name, $selected = null, $options = array(), $optional = false)
    {
        $list = [];
        foreach (Auth::user()->addresses as $address) {
            $list[$address->id] = $address->name.' ('.$address->address_one.')';
        }

        if ($optional) {
            array_unshift($list, 'Select One...');
        }

        return $this->select($name, $list, $selected, $options);
    }

    /**
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectMonthNumeric($name, $selected = null, $options = array(), $optional = false)
    {
        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];
        $list = [];
        foreach ($months as $monthId => $month) {
            $list[$monthId] = $monthId.' - '.$month;
        }

        if ($optional) {
            array_unshift($list, 'Select One...');
        }

        return $this->select($name, $list, $selected, $options);
    }

    /**
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectMonth($name, $selected = null, $options = array(), $optional = false)
    {
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];
        $list = [];
        foreach ($months as $monthId => $month) {
            $list[$monthId] = $month;
        }

        if ($optional) {
            array_unshift($list, 'Select One...');
        }

        return $this->select($name, $list, $selected, $options);
    }

    /**
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectFutureYear($name, $selected = null, $options = array(), $optional = false)
    {
        $list = [];
        $currentYear = Carbon::now()->year;
        for ($x = 1; $x <= 5; $x++) {
            $currentYear += 1;
            $list[$currentYear] = $currentYear;
        }

        if ($optional) {
            array_unshift($list, 'Select One...');
        }

        return $this->select($name, $list, $selected, $options);
    }
}