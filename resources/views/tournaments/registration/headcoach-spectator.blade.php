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
                            'url' => '/tournaments/'.$tournament->slug.'/registration/spectator',
                            'class' => 'form-horizontal',
                            'role' => 'form'
                        ]) !!}

                        @if($tournament->isRegisteredAsSpectator(Auth::user()) === false)
                            <div class="row">
                                <div class="col-md-12 p-t-30">
                                    <div class="alert alert-info">
                                    <p class="text-center p-b-10">If you're planning to attend this tournament you'll need to register yourself as well.  Would you like to go ahead and do that?</p>
                                    <div class="row">
                                        <div class="col-md-3 col-md-offset-5 col-sm-3 col-md-offset-5 col-xs-6 col-xs-offset-3">
                                            <div class="checkbox check-success">
                                                {!! Form::checkbox('registering_as_current_user', 1, old('registering_as_current_user'), ['id' => 'registering-as-current-user']) !!}
                                                <label for="registering-as-current-user" style="color: #246a8e">Yes, register me</label>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            {!! Form::hidden('registering_as_current_user', 0) !!}
                        @endif

                        @include('tournaments.registration.partials.spectator-form', [
                            'tournament' => $tournament
                        ])
                        <div class="row">
                            <div class="col-md-12 text-center p-t-30">
                                <input class="btn btn-primary btn-cons" type="submit" name='save' value="Save"/>
                                <input class="btn btn-primary btn-cons m-l-25" type="submit" name='save-and-add' value="Save & Add Another"/>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@js
    $(document).ready(function() {
        // hide/show name, address, etc. for the head coach
        $('#registering-as-current-user').change(function() {
            if ($(this).is(':checked')) {
                $('#non-current-user-registration').hide();
            } else {
                $('#non-current-user-registration').show();
            }
        });
        if ($('#registering-as-current-user').is(':checked')) {
            $('#non-current-user-registration').hide();
        } else {
            $('#non-current-user-registration').show();
        }
    });
@endjs