@component('mail::message')
# Graduating Players

**{{ $group->name }}** has {{ count($players) }} {{ $grade }} grader(s) for the {{ $season->name }} season.  Their information is attached.

@endcomponent