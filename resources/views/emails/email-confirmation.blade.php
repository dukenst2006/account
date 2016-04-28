@extends('emails.simple')

@section('body')

    @include('emails.theme.header', [
        'header' => 'Confirm your email address'
    ])

    @include('emails.theme.text-block', [
        'body' => 'To verify your email address, please follow '. EmailTemplate::link(url('register/confirm/'.$user->guid), 'this link') .'.'
    ])

@endsection