@extends('emails.simple')

@section('body')
    <?php
        // Serialized objects need to be re-instantiated in order
        // to have a successful database connection
        $outstandingAtLeast = Config::get('biblebowl.reminders.notify-office-of-outstanding-registration-payments-after');
        $relativeTime = $outstandingAtLeast.' ago';
        $playersRegistrationUnpaidSince = new \Carbon\Carbon($relativeTime);
        $season = \BibleBowl\Season::current()->first();
        $groupCount = \BibleBowl\Group::hasPendingRegistrationPayments($season, $playersRegistrationUnpaidSince)->count();
    ?>

    @include('emails.theme.header', [
        'header' => 'Outstanding Registration Fees'
    ])

    <?php
    $text = 'is <strong>'.EmailTemplate::link(url('/admin/groups/outstanding-registration-fees'), number_format($groupCount).'</strong> group');
        if ($groupCount > 1) {
            $text = 'are <strong>'.EmailTemplate::link(url('/admin/groups/outstanding-registration-fees'), number_format($groupCount).'</strong> groups');
        }
    ?>
    @include('emails.theme.text-block', [
        'body' => 'There '.$text.' with outstanding registration fees of at least <strong>'.$outstandingAtLeast.'</strong>.  The system has already sent several payment reminders so they may require further followup.  To avoid future notifications, they must either pay their registration fees or flag their players as "Inactive" within their player roster.'
    ])

@endsection