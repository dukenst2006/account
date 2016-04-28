@extends('emails.simple')

@section('title', 'Reset your password')

@section('body')
Click {!! EmailTemplate::link(url('password/reset/'.$token), 'this link') !!} to reset your password.
@endsection