@extends('layouts.master')

@section('title', 'Quizmasters - '.$tournament->name)

@section('content')
    <div class="content" id="page" v-cloak>
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Add <span class="semi-bold">A Quizmaster</span></h4>
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
                        <div class="row p-t-20">
                            <div class="col-md-3">
                                Quizmaster:
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Name</label>
                                        <span class="help"></span>
                                        <div class="controls row p-b-20">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First', 'maxlength' => 32, 'autofocus']) !!}
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last', 'maxlength' => 32]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <label class="form-label">Email</label>
                                        <span class="help"></span>
                                        <div class="controls p-b-20">
                                            {!! Form::email('email', null, ['class' => 'form-control', 'maxlength' => 128]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <label class="form-label">Gender</label>
                                        <span class="help"></span>
                                        <div class="controls p-b-20">
                                            @include('partials.forms.gender')
                                        </div>
                                    </div>
                                </div>
                                <p>We'll reach out to this quizmaster via email once you hit save so they can provide their quizzing preferences.  Their response is not required.</p>
                            </div>
                        </div>
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

@includeVueJs
@includeJs(elixir('assets/js/tournaments/quizmasters.js'))
@jsData
    var quizmasters = [];
@endjsData