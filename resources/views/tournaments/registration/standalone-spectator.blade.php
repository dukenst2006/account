@extends('layouts.master')

@section('title', 'Adult/Family Registration - '.$tournament->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Register <span class="semi-bold">To Spectate</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        @include('tournaments.partials.tournament-summary', [
                            'tournament' => $tournament
                        ])
                        {!! Form::open([
                            'url' => '/tournaments/'.$tournament->slug.'/registration/standalone-spectator',
                            'class' => 'form-horizontal',
                            'role' => 'form'
                        ]) !!}
                        <div class="row p-t-20">
                            <div class="col-md-3">
                                Adult:
                            </div>
                            <div class="col-md-9">
                                <div class="row form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label">Which group are you with?</label>
                                        <span class="help">Whether they have teams at this tournament or not</span>
                                        <div class="controls">
                                            {!! Form::selectGroup($tournament->program_id, 'group_id', old('group_id'), ['class' => 'form-control'], true) !!}
                                        </div>
                                    </div>
                                </div>
                                @if(Auth::user() == null)
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="form-label">Name</label>
                                        <span class="help"></span>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => 'First', 'maxlength' => 64, 'autofocus']) !!}
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => 'Last', 'maxlength' => 64]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="form-label">Email</label>
                                        <span class="help"></span>
                                        <div class="controls">
                                            {!! Form::email('email', old('email'), ['class' => 'form-control', 'maxlength' => 64]) !!}
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="row form-group">
                                    <div class="col-md-6 col-sm-6">
                                        <label class="form-label">T-Shirt Size</label>
                                        <span class="help"></span>
                                        <div class="controls">
                                            {!! Form::selectShirtSize('shirt_size', old('shirt_size'), ['class' => 'form-control']) !!}<br/>
                                        </div>
                                    </div>
                                    @if(Auth::user() == null)
                                    <div class="col-md-6 col-sm-6">
                                        <label class="form-label">Gender</label>
                                        <span class="help"></span>
                                        <div class="controls">
                                            @include('partials.forms.gender')
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="checkbox check-primary">
                                    {!! Form::checkbox('register-family', 1, old('register-family'), ['id' => 'register-family']) !!}
                                    <label for="register-family">Register your spouse and/or minors</label>
                                </div>
                            </div>
                        </div>
                        <div class="row p-t-20">
                            <div class="col-md-3">
                                Spouse:
                            </div>
                            <div class="col-md-9">
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="form-label">Name</label>
                                        <span class="help"></span>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                {!! Form::text('spouse_first_name', old('spouse_first_name'), ['class' => 'form-control', 'placeholder' => 'First', 'maxlength' => 64]) !!}
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6 col-sm-6">
                                        <label class="form-label">T-Shirt Size</label>
                                        <span class="help"></span>
                                        <div class="controls">
                                            {!! Form::selectShirtSize('spouse_shirt_size', old('spouse_shirt_size'), ['class' => 'form-control']) !!}<br/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <label class="form-label">Gender</label>
                                        <span class="help"></span>
                                        <div class="controls">
                                            @include('partials.forms.gender')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                @if($adultFee > 0)
                                    <h4 class="m-b-25">
                                        Fee due: ${{ number_format($adultFee) }}
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