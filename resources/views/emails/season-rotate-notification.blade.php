@component('mail::message')
# {{ $nextSeasonName }} Season

The next season is scheduled to begin on {{ $willRotateOn }}.  You can change this rotation date in the [admin settings]({{ url('admin/settings') }}).

Here's a quick reminder of what the registration fees are currently set to.  If these need to change for the upcoming season, don't forget to change them before the season rotates.

@foreach ($programs as $program)
 * {{ $program->name }}: ${{ $program->registration_fee }}
@endforeach

@endcomponent