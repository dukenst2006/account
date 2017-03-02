<?php
$reportLink = '['.number_format($groupCount).']('.url('/admin/groups/outstanding-registration-fees').')';
$text = 'is **'.$reportLink.'** group';
if ($groupCount > 1) {
    $text = 'are **'.$reportLink.'** groups';
}
?>

@component('mail::message')
# Outstanding Seasonal Registration Fees

There {{ $text }} with outstanding registration fees of at least **{{ $outstandingAtLeast }}**.  The system has already sent several payment reminders so they may require further followup.

To avoid future notifications, they must either pay their registration fees or flag their players as "Inactive" within their player roster.
@endcomponent