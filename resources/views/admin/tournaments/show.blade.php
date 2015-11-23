@extends('layouts.master')

@section('title', $tournament->name.' - '.$tournament->season->name)

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h3 class="semi-bold p-t-10 m-l-15" style="margin-bottom: 0">{{ $tournament->name }}</h3>
                <div class="b-grey b-b m-t-10"></div>
            </div>
            <div class="grid-body no-border p-t-20">
                <div class="row m-t-10">
                    <div class="col-md-12">
                        @if(!$tournament->active)
                            <div class="alert text-center">Once you've finished configuring this tournament, be sure to make it "Active" so that people can register once registration is open.</div>
                        @endif
                        <div class="row">
                            <div class="col-md-4 b-grey b-r">
                                <h5><i class="fa fa-calendar"></i> <span class="semi-bold">When</span></h5>
                                <div class="m-l-20 m-b-20">{{ $tournament->dateSpan() }}</div>
                                <h5><i class="fa fa-pencil"></i> <span class="semi-bold">Registration</span></h5>
                                <div class="m-l-20 m-b-10">
                                    Status:
                                    @if($tournament->isRegistrationOpen())
                                        <span class="text-success">Open</span><br/>
                                        Closes: {{ $tournament->registration_end->toFormattedDateString() }}
                                    @else
                                        <span class="text-danger">Closed</span><br/>
                                        @if(\Carbon\Carbon::now()->lte($tournament->registration_start))
                                            Dates: {{ $tournament->registrationDateSpan() }}
                                        @endif
                                    @endif

                                </div>
                                <div class="text-center m-t-20">
                                    <a href="{{ route('admin.tournaments.edit', [$tournament->id]) }}" class="btn btn-small btn-primary">Edit</a>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5><i class="fa fa-users"></i> <span class="semi-bold">Events</span></h5>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{ route('admin.tournaments.events.create', [$tournament->id]) }}" class="btn btn-primary btn-small">Add Event</a>
                                    </div>
                                </div>
                                <table class="table no-more-tables">
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-center">Price</th>
                                    </tr>
                                    @foreach ($tournament->events as $event)
                                        <tr>
                                            <td><a href="/admin/tournaments/event/{{ $event->id }}">{{ $event->type->name }}</a></td>
                                            <td class="text-center">{{ is_null($event->price_per_participant) ? '-' : '$'.money_format($event->price_per_participant) }} / {{ ucwords($event->type->participant_type) }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center muted p-t-20" style="font-style:italic; font-size: 90%;">
                        Last Updated: {{ $tournament->updated_at->format('F j, Y, g:i a') }} |
                        Created: {{ $tournament->created_at->format('F j, Y, g:i a') }}
                </div>
            </div>
        </div>
    </div>
@endsection