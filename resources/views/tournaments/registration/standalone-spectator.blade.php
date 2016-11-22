@extends('layouts.master')

@section('title', 'Adult/Family Registration - '.$tournament->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                @include('tournaments.partials.tournament-summary', [
                    'tournament' => $tournament
                ])
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Register <span class="semi-bold">A Spectator</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::open([
                            'url' => '/tournaments/'.$tournament->slug.'/registration/standalone-spectator',
                            'class' => 'form-horizontal',
                            'role' => 'form'
                        ]) !!}

                        {!! Form::hidden('registering_as_current_user', Auth::user() == null ? 0 : 1) !!}

                        @include('tournaments.registration.partials.spectator-form', [
                            'tournament' => $tournament
                        ])
                        <div class="row">
                            <div class="col-md-12 text-center">
                                @if($adultFee > 0)
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