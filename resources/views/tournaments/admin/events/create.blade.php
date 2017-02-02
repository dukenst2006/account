@extends('layouts.master')

@section('title', 'New Event')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>New <span class="semi-bold">Event</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::open(['route' => ['admin.tournaments.events.store', $tournament->id], 'role' => 'form']) !!}
                            <div class="row">
                                <div class="col-md-2">
                                    Tournament
                                </div>
                                <div class="col-md-10 p-b-10">
                                    <h4 class="semi-bold no-margin">
                                        {{ $tournament->name }}
                                    </h4>
                                    <p>{{ $tournament->dateSpan() }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p>Teams are automatically opted-in to team events.  Player events will be opt-in events during registration.</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label">Type <span class="required">*</span></label>
                                    <span class="help"></span>
                                    <div class="controls">
                                        @foreach($eventTypes as $eventType)
                                            <div class="radio">
                                                {!! Form::radio('event_type_id', $eventType->id, null, ['id' => 'eventType'.$eventType->id, 'class' => ($eventType->participant_type_id == \BibleBowl\ParticipantType::PLAYER ? 'canHaveFee canHaveRequiredParticipation' : '')]) !!}
                                                <label for="eventType{{ $eventType->id }}"><strong>{{ $eventType->name }}</strong> ({{ $eventType->participantType->name }} event)</label>
                                            </div>
                                            <br/>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @include('tournaments.admin.events.form')

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
@js
    $(document).ready(function () {
        $('input[name="event_type_id"]').change(function () {
            if ($(this).hasClass('canHaveFee')) {
                $('#price-field').show();
            } else {
                $('#price-field').hide();
                $('input[name="price_per_participant"]').val('');
            }

            if ($(this).hasClass('canHaveRequiredParticipation')) {
                $('#required-participation').show();
            } else {
                $('#required-participation').hide();
                $('input[name="required"]').prop('checked', false);
            }
        });
    });
@endjs