@extends('layouts.master')

@section('title', 'Quizmasters - '.$tournament->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Choose <span class="semi-bold">Your Teams</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('tournaments.partials.tournament-summary', [
                            'tournament' => $tournament
                        ])
                        @include('tournaments.partials.teams-summary', [
                            'teamSet' => $teamSet
                        ])
                        <div class="row p-t-10">
                            <div class="col-md-3">
                                Quizmaster(s):
                            </div>
                            <div class="col-md-9">
                                <div class="row p-b-15 b-b b-grey">
                                    <div class="col-md-5">
                                        {!! Form::text('first_name[]', old('first_name[]'), ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-md-2 text-center p-t-10">
                                        {!! Form::text('last_name[]', old('last_name[]'), ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-md-2 text-center p-t-10">
                                        {!! Form::text('email[]', old('email[]'), ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@js

@endjs