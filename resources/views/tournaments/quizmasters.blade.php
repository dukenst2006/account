@extends('layouts.master')

@section('title', 'Quizmasters - '.$tournament->name)

@section('content')
    <div class="content" id="page" v-cloak>
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Choose <span class="semi-bold">Your Quizmasters</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('tournaments.partials.tournament-summary', [
                            'tournament' => $tournament
                        ])
                        @include('tournaments.partials.teams-summary', [
                            'teamSet' => $teamSet
                        ])
                        <div class="row p-t-20">
                            <div class="col-md-3">
                                Quizmaster(s):
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    @include('tournaments.partials.manage-quizmasters')
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center p-t-30">
                                <button class="btn btn-primary btn-cons" type="submit">Save & Continue</button>
                            </div>
                        </div>
                        {!! Form::open(['class' => 'form-horizontal', 'role' => 'form']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@includeVueJs
@includeJs(elixir('assets/js/tournaments/quizmasters.js'))
@jsData
    var quizmasters = [];
@endjsData