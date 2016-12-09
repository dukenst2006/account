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
                        @if($tournament->isInactive())
                            <div class="alert text-center">Once you've finished configuring this tournament, be sure to make it "Active" so that people can register once registration is open.</div>
                        @endif
                        <div class="row">
                            <div class="col-md-4 col-sm-4 b-grey b-r">
                                <h5><i class="fa fa-calendar"></i> <span class="semi-bold">When</span></h5>
                                <div class="m-l-20 m-b-20">{{ $tournament->dateSpan() }}</div>
                                <h5><i class="fa fa-pencil"></i> <span class="semi-bold">Registration</span></h5>
                                <div class="m-l-20 m-b-20">
                                    Status:
                                    @if($tournament->isRegistrationOpen())
                                        <span class="text-success">Open</span><br/>
                                        Closes: {{ $tournament->registration_end->toFormattedDateString() }}
                                        @if($tournament->hasEarlyBirdRegistration())
                                            <br/>Early bird ends: {{ $tournament->earlybird_ends->format('M j, Y') }}
                                        @endif
                                    @else
                                        <span class="text-danger">Closed</span><br/>
                                        @if(\Carbon\Carbon::now()->lte($tournament->registration_start))
                                            Dates: {{ $tournament->registrationDateSpan() }}
                                        @endif
                                    @endif
                                </div>
                                <h5><i class="fa fa-users"></i> <span class="semi-bold">Teams</span></h5>
                                <div class="m-l-20 m-b-20">
                                    Max of {{ number_format($tournament->max_teams) }}<br/>
                                    @if($tournament->teamsWillLock())
                                        @if($tournament->teamsAreLocked())
                                            Changes are <span class="text-danger">locked</span>
                                        @else
                                            Lock on: {{ $tournament->lock_teams->toFormattedDateString() }}
                                        @endif
                                    @endif
                                </div>
                                <div class="text-center m-t-20">
                                    <a href="{{ route('admin.tournaments.edit', [$tournament->id]) }}" class="btn btn-small btn-primary">Edit</a>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-8 m-b-20">

                                <div class="row">
                                    <div class="col-md-4 col-sm-4 text-center">
                                        <a href="#">
                                            <h2 class="semi-bold text-primary no-margin p-t-35 p-b-10">{{ number_format($tournament->eligibleTeams()->count()) }}</h2>
                                            <div class="tiles-title blend p-b-25">TEAMS</div>
                                        </a>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 text-center">
                                        <a href="#">
                                            <h2 class="semi-bold text-success no-margin p-t-35 p-b-10">{{ number_format($tournament->eligiblePlayers()->count()) }}</h2>
                                            <div class="tiles-title blend p-b-25">PLAYERS</div>
                                        </a>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 text-center">
                                        <h2 class="semi-bold text-warning no-margin p-t-35 p-b-10">
                                            {{ number_format($tournament->eligibleQuizmasters()->count()) }}
                                        </h2>
                                        <div class="tiles-title blend p-b-25">QUIZMASTERS</div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <h5><i class="fa fa-trophy"></i> <span class="semi-bold">Events</span></h5>
                                    </div>
                                    <div class="col-md-6 col-xs-6 text-right">
                                        <a href="{{ route('admin.tournaments.events.create', [$tournament->id]) }}" class="btn btn-primary btn-small">Add Event</a>
                                    </div>
                                </div>
                                <table class="table">
                                    <tr>
                                        <th>Type</th>
                                        <th class="text-center hidden-xs hidden-sm">Price</th>
                                        <th class="text-center">Participants</th>
                                        <th width="20%"></th>
                                    </tr>
                                    @foreach ($tournament->events()->with('type')->get() as $event)
                                        <tr>
                                            <td>{{ $event->type->name }}</td>
                                            <td class="text-center hidden-xs hidden-sm">{{ $event->displayPrice() }}</td>
                                            <td class="text-center">
                                                @if($event->type->participant_type_id == \BibleBowl\ParticipantType::PLAYER)
                                                    {{ number_format($event->eligiblePlayers()->count()) }}
                                                @endif
                                            </td>
                                            <td>
                                                {!! Form::open(['url' => '/admin/tournaments/'.$tournament->id.'/events/'.$event->id, 'method' => 'delete']) !!}
                                                @if($event->type->participant_type_id == \BibleBowl\ParticipantType::PLAYER)
                                                    <a href="{{ route('admin.tournaments.events.edit', [$tournament->id, $event->id]) }}" class="fa fa-edit" id="edit-{{ $event->id }}"></a>
                                                @endif
                                                <a class="fa fa-trash-o p-l-20" onclick="$(this).closest('form').submit();" id="delete-{{ $event->id }}"></a>
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center muted p-t-20" style="font-style:italic; font-size: 90%;">
                        Last Updated: {{ $tournament->updated_at->timezone(Auth::user()->settings->timeszone())->format('F j, Y, g:i a') }} |
                        Created: {{ $tournament->created_at->timezone(Auth::user()->settings->timeszone())->format('F j, Y, g:i a') }}
                </div>
            </div>
        </div>
    </div>
@endsection