<?php namespace BibleBowl\Presentation;

use Auth;
use BibleBowl\EventType;
use BibleBowl\Group;
use BibleBowl\GroupType;
use BibleBowl\ParticipantType;
use BibleBowl\Program;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Html\FormBuilder;

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
        return '<div class="input-group transparent">
            <span class="input-group-addon">
                <i class="fa fa-usd"></i>
            </span>'.$this->number($name, $value, $options).'</div>';
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
     * Create a file input field.
     *
     * @param  string  $name
     * @param  array   $value
     * @param  array   $options
     * @return string
     */
    public function phone($name, $value = null, $options = array())
    {
        $defaults = [
            'maxlength'     => '10',
            'pattern'       => '[0-9]{10}',
            'placeholder'   => 'only digits'
        ];
        $options = array_merge($defaults, $options);
        return $this->input('tel', $name, $value, $options);
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
        $list = [
            'America/New_York'      => 'Eastern',
            'America/Chicago'       => 'Central',
            'America/Denver'        => 'Mountain',
            'America/Phoenix'       => 'Mountain (no DST)',
            'America/Los_Angeles'   => 'Pacific',
            'America/Anchorage'     => 'Alaska',
            'America/Adak'          => 'Hawaii',
            'Pacific/Honolulu'      => 'Hawaii (no DST)'
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
        $elementarySchool = [
            '3' => Describer::describeGrade(3),
            '4' => Describer::describeGrade(4),
            '5' => Describer::describeGrade(5)
        ];
        $middleSchool = [
            '6' => Describer::describeGrade(3),
            '7' => Describer::describeGrade(4),
            '8' => Describer::describeGrade(5)
        ];
        $highSchool = [
            '9' => Describer::describeGrade(9),
            '10' => Describer::describeGrade(10),
            '11' => Describer::describeGrade(11),
            '12' => Describer::describeGrade(12)
        ];

        if ($programId == Program::BEGINNER) {
            $list = $elementarySchool;
        } elseif ($programId == Program::TEEN) {
            $list = $middleSchool + $highSchool;
        } else {
            $list = $elementarySchool + $middleSchool + $highSchool;
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
        $list = [];
        foreach (ParticipantType::orderBy('name')->get() as $participantType) {
            $list[$participantType->id] = $participantType->name;
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
    public function selectGroupType($name, $selected = null, $options = array(), $optional = false)
    {
        $list = [];
        foreach (GroupType::orderBy('name')->get() as $groupType) {
            $list[$groupType->id] = $groupType->name;
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
    public function selectGender($name, $selected = null, $options = array(), $optional = false)
    {
        $list = [
            Html::GENDER_MALE => Describer::describeGender(Html::GENDER_MALE),
            Html::GENDER_FEMALE => Describer::describeGender(Html::GENDER_FEMALE)
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
    public function selectGroup(int $programId, $name, $selected = null, $options = array(), $optional = false)
    {
        $availableGroups = Group::active()
            ->byProgram($programId)
            ->orderBy('name')
            ->with('meetingAddress')
            ->get();

        $groups = $optional ? ['' => ''] : [];
        foreach ($availableGroups as $group) {
            $groups[$group->id] = $group->name.' - '.$group->meetingAddress->city.', '.$group->meetingAddress->state;
        }

        return $this->select($name, $groups, $selected, $options);
    }
}
