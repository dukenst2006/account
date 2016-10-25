<?php

return [

    'officeEmail' => env('OFFICE_EMAIL', 'office@biblebowl.org'),

    'groups' => [

        // the range in miles to suggest groups near a given address
        'nearby' => 80,
    ],

    'reminders' => [

        // an email gets dispatched to groups to remind them of outstanding
        // player fees.  A group must have a player who has gone unpaid for
        // at least this time frame in order to get the email
        'remind-groups-of-pending-payments-after' => '5 weeks',

        // the next step is sending the national office a notification of groups
        // who are have continued to not pay their fees
        'notify-office-of-outstanding-registration-payments-after' => '9 weeks',

    ],

    // cleanup orphan accounts after the specified
    'cleanup-orphan-accounts-after' => '30' // days

];
