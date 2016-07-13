@extends('layouts.master')

@section('title', 'Quizmaster Registration - '.$tournament->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Choose <span class="semi-bold">Your Quizmasters</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        @include('tournaments.partials.tournament-summary', [
                            'tournament' => $tournament
                        ])
                        {!! Form::open([
                            'url' => '/tournaments/'.$tournament->slug.'/registration/standalone-quizmaster',
                            'class' => 'form-horizontal',
                            'role' => 'form'
                        ]) !!}
                        <div class="row p-t-20">
                            <div class="col-md-3">
                                Quizmaster:
                            </div>
                            <div class="col-md-9">
                                <div class="row form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label">Which group are you with?</label>
                                        <span class="help">Whether they have teams at this tournament or not</span>
                                        <div class="controls">
                                            {!! Form::select('group_id', $groups, null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label class="form-label">T-Shirt Size</label>
                                        <span class="help"></span>
                                        <div class="controls">
                                            {!! Form::selectShirtSize('shirt_size', old('shirt_size'), ['class' => 'form-control']) !!}<br/>
                                        </div>
                                    </div>
                                </div>
                                @include('tournaments.registration.partials.quizzing-preferences')
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                @if($hasFee)
                                    <h4 class="m-b-25">
                                        Fee due: ${{ number_format($fee) }}
                                    </h4>
                                    <button class="btn btn-primary btn-cons" type="submit">Continue</button>
                                @else
                                    <button class="btn btn-primary btn-cons" type="submit">Submit</button>
                                @endif
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection