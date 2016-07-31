@extends('layouts.master')

@section('title', 'Quizmasters - '.$tournament->name)

@section('content')
    <div class="content" id="page" v-cloak>
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Register <span class="semi-bold">A Spectator</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        @include('tournaments.partials.tournament-summary', [
                            'tournament' => $tournament
                        ])
                        {!! Form::open([
                            'url' => '/tournaments/'.$tournament->slug.'/registration/quizmaster',
                            'class' => 'form-horizontal',
                            'role' => 'form'
                        ]) !!}
                        @include('tournaments.registration.partials.spectator-form', [
                            'tournament' => $tournament
                        ])
                        <div class="row">
                            <div class="col-md-12 text-center p-t-30">
                                <button class="btn btn-primary btn-cons" type="submit">Save & Continue</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection