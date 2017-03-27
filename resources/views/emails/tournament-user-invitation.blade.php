@component('mail::message')
# {{ $header }}

{{ $invitationText }}

@component('mail::button', ['url' => url('invitation/'.$invitation->guid.'/accept'), 'color' => 'green'])
Accept
@endcomponent

@component('mail::button', ['url' => url('invitation/'.$invitation->guid.'/decline'), 'color' => 'red'])
Decline
@endcomponent

@endcomponent