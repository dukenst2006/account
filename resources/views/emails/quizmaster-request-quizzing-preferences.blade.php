@extends('emails.simple')

@section('body')

    @include('emails.theme.header', [
        'header' => 'Quizzing Preferences'
    ])

    @include('emails.theme.text-block', [
        'body' => "You've been added as a quizmaster for <strong>".$group->name."</strong>.  Please ". EmailTemplate::link(url('tournaments/'.$tournament->slug.'/registration/quizmaster-preferences/'.$tournamentQuizmaster->guid), 'tell us about your quizzing preferences') ." and we'll do our best to accomidate them."
    ])

    @include('emails.partials.tournament-overview')
@endsection