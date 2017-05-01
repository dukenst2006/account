@component('mail::message')
# Graduating Players

**{{ $group->name }}** didn't have any {{ $grade }} graders for the {{ $season->name }} season.

@endcomponent