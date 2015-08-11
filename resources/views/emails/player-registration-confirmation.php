@extends('emails.simple')

@section('title', 'New Player Registration for '.$group->name)

@section('body')
    To verify your email address, please follow {!! EmailTemplate::link(url('register/confirm/'.$user->guid), 'this link') !!}.
@endsection