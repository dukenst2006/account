<?php namespace BibleBowl\Presentation;

use Auth;
use BibleBowl\EventType;
use BibleBowl\Program;
use Carbon\Carbon;
use Illuminate\Html\FormBuilder;
use DateTimeZone;
use DateTime;

class Form extends FormBuilder
{

    /**
     * Create a file input field.
     *
     * @param  string  $name
     * @param  array   $value
     * @param  array   $options
     * @return string
     */
    public function money($name, $value = null, $options = array())
    {
        return $this->number($name, $value, $options);
    }

    /**
     * Create a file input field.
     *
     * @param  string  $name
     * @param  array   $value
     * @param  array   $options
     * @return string
     */
    public function number($name, $value = null, $options = array())
    {
        $defaults = [
            'step' => 'any'
        ];
        $options = array_merge($defaults, $options);
        return $this->input('number', $name, $value, $options);
    }

    /**
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectTimezone($name, $selected = null, $options = array(), $optional = false)
    {
        $list = [];
        foreach (DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, 'US') as $timezoneIdentifier) {
            $timezone = new DateTimeZone($timezoneIdentifier);
            $offsetInHours = $timezone->getoffset(new DateTime()) / (3600);
            $list[$timezoneIdentifier] = '(UTC '.$offsetInHours.':00) '.$timezoneIdentifier;
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
                'YS' => Describer::describeShirtSize('YS'),
                'YM' => Describer::describeShirtSize('YM'),
                'YL' => Describer::describeShirtSize('YL'),
            ],
            'Adult' => [
                'S' => Describer::describeShirtSize('S'),
                'M' => Describer::describeShirtSize('M'),
                'L' => Describer::describeShirtSize('L'),
                'XL' => Describer::describeShirtSize('XL'),
                'XXL' => Describer::describeShirtSize('XXL'),
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
    public function selectGrade($name, $selected = null, $options = array(), $optional = false, $programId = null)
    {
        $middleSchool = [
            '3' => Describer::describeGrade(3),
            '4' => Describer::describeGrade(4),
            '5' => Describer::describeGrade(5)
        ];
        $highSchool = [
            '6' => Describer::describeGrade(6),
            '7' => Describer::describeGrade(7),
            '8' => Describer::describeGrade(8),
            '9' => Describer::describeGrade(9),
            '10' => Describer::describeGrade(10),
            '11' => Describer::describeGrade(11),
            '12' => Describer::describeGrade(12)
        ];

        if ($programId == Program::BEGINNER) {
            $list = $middleSchool;
        } elseif ($programId == Program::TEEN) {
            $list = $highSchool;
        } else {
            $list = array_merge($middleSchool, $highSchool);
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

    /**
     * @param       $name
     * @param null  $selected
     * @param array $options
     *
     * @return string
     */
    public function selectParticipantType($name, $selected = null, $options = array(), $optional = false)
    {
        $participantTypes = [
            EventType::PARTICIPANT_PLAYER   => 'Individual Players',
            EventType::PARTICIPANT_TEAM     => 'Teams'
        ];
        $list = [];
        foreach ($participantTypes as $type => $label) {
            $list[$type] = $label;
        }

        if ($optional) {
            array_unshift($list, 'Select One...');
        }

        return $this->select($name, $list, $selected, $options);
    }
}
