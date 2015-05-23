@extends('emails.simple')

@section('title', 'Confirm your email address')

@section('body')
    To verify your email address, please follow {!! EmailTemplate::link(url('register/confirm/'.$user->guid), 'this link') !!}.
@endsection