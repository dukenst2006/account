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
                            <div class="col-md-4 b-grey b-r">
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
                                    0 of {{ number_format($tournament->max_teams) }} registered<br/>
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
                            <div class="col-md-8">
                                @if(count($participantFees) > 0)
                                <h5><i class="fa fa-usd"></i> <span class="semi-bold">Fees</span></h5>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th style="width:35%"></th>
                                            <th style="width:30%" class="text-center">Online Registration Required</th>
                                            @if($tournament->hasEarlyBirdRegistration())
                                            <th style="width:20%" class="text-center">Early Bird Fee</th>
                                            @endif
                                            <th style="width:15%" class="text-center">On-site Fee</th>
                                        </tr>
                                    @foreach ($participantFees as $fee)
                                        <tr>
                                            <td>
                                                {{ $fee->participantType->name }}
                                            </td>
                                            <td class="text-center">
                                                @if($fee->requires_registration)
                                                    <i class="fa fa-check"></i>
                                                    @if($fee->fee > 0)
                                                        ${{ $fee->fee }}
                                                    @endif
                                                @endif
                                            </td>
                                            @if($tournament->hasEarlyBirdRegistration())
                                            <td class="text-center">
                                                @if($fee->earlybird_fee > 0)
                                                    ${{ $fee->earlybird_fee }}
                                                @endif
                                            </td>
                                            @endif
                                            <td class="text-center">
                                                @if($fee->onsite_fee > 0)
                                                    ${{ $fee->onsite_fee }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5><i class="fa fa-trophy"></i> <span class="semi-bold">Events</span></h5>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{ route('admin.tournaments.events.create', [$tournament->id]) }}" class="btn btn-primary btn-small">Add Event</a>
                                    </div>
                                </div>
                                <table class="table">
                                    <tr>
                                        <th>Type</th>
                                        <th class="text-center">Price</th>
                                        <th width="20%"></th>
                                    </tr>
                                    @foreach ($tournament->events()->with('type')->get() as $event)
                                        <tr>
                                            <td>{{ $event->type->name }}</td>
                                            <td class="text-center">{{ $event->displayPrice() }}</td>
                                            <td>
                                                {!! Form::open(['url' => '/admin/tournaments/'.$tournament->id.'/events/'.$event->id, 'method' => 'delete']) !!}
                                                <a href="{{ route('admin.tournaments.events.edit', [$tournament->id, $event->id]) }}" class="fa fa-edit" id="edit-{{ $event->id }}"></a>
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