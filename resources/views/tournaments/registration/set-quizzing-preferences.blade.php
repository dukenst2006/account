@extends('layouts.master')

@section('title', 'Quizzing Preferences - '.$tournament->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Quizzing <span class="semi-bold">Preferences</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        @include('tournaments.partials.tournament-summary', [
                            'tournament' => $tournament
                        ])
                        @include('tournaments.partials.group-summary', [
                            'group' => $group
                        ])
                        {!! Form::open([ 'class' => 'form-horizontal', 'role' => 'form' ]) !!}
                        <div class="row p-t-20">
                            <div class="col-md-4 col-md-offset-3">
                                <label class="form-label">T-Shirt Size</label>
                                <span class="help"></span>
                                <div class="controls">
                                    {!! Form::selectShirtSize('shirt_size', old('shirt_size', $quizmaster->shirt_size), ['class' => 'form-control']) !!}<br/>
                                </div>
                            </div>
                        </div>
                        <div class="row p-t-20">
                            <div class="col-md-3">
                                Quizzing Preferences:
                            </div>
                            <div class="col-md-9">
                                @include('tournaments.registration.partials.quizzing-preferences', [
                                    'quizzingPreferences' => $quizzingPreferences
                                ])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary btn-cons" type="submit">Save</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection