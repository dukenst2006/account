@extends('emails.simple')

@section('body')

    @include('emails.theme.header', [
        'header' => $header
    ])

    <p>{{ $invitationText }}</p>
    <p>{!! EmailTemplate::link(url('invitation/'.$invitation->guid.'/accept'), 'Accept') !!} or {!! EmailTemplate::link(url('invitation/'.$invitation->guid.'/decline'), 'Decline') !!}</p>

@endsection