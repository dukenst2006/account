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
                        {!! Form::open(['route' => ['tournaments.events.store', $tournament->id], 'role' => 'form']) !!}
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
                                <div class="col-md-12 form-group">
                                    <label class="form-label">Event Type <span class="required">*</span></label>
                                    <span class="help"></span>
                                    <div class="controls">
                                        @foreach($eventTypes as $eventType)
                                            <div class="radio">
                                                {!! Form::radio('event_type_id', $eventType->id, null, ['id' => 'eventType'.$eventType->id]) !!}
                                                <label for="eventType{{ $eventType->id }}"><strong>{{ $eventType->name }}</strong> (priced per {{ $eventType->participant_type }})</label>
                                            </div>
                                            <br/>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @include('tournaments.events.form')

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

@includeCss(/assets/plugins/bootstrap-datepicker/css/datepicker.min.css)
@includeJs(/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js)
@js
    $(document).ready(function () {
        $('.input-append.date').datepicker({
            autoclose: true,
            todayHighlight: true
        });
    });
@endjs