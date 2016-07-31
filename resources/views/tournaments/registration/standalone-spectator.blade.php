@extends('layouts.master')

@section('title', 'Adult/Family Registration - '.$tournament->name)

@section('content')
    <div class="content">
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
                            'url' => '/tournaments/'.$tournament->slug.'/registration/standalone-spectator',
                            'class' => 'form-horizontal',
                            'role' => 'form'
                        ]) !!}
                        @include('tournaments.registration.partials.spectator-form', [
                            'tournament' => $tournament
                        ])
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