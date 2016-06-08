@extends('layouts.master')

@section('title', $tournament->season->name.' - '.$tournament->name)

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
                            <div class="alert text-center">This tournament won't be publicly visible until it is made active.</div>
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
                                @if($tournament->teamsWillLock())
                                <h5><i class="fa fa-users"></i> <span class="semi-bold">Teams</span></h5>
                                <div class="m-l-20 m-b-20">

                                        @if($tournament->teamsAreLocked())
                                            Changes are <span class="text-danger">locked</span>
                                        @else
                                            Editable until {{ $tournament->lock_teams->toFormattedDateString() }}
                                        @endif
                                </div>
                                @endif
                                @if(count($events) > 0)
                                <h5><i class="fa fa-trophy"></i> <span class="semi-bold">Events</span></h5>
                                <div class="m-l-20 m-b-20">
                                    <ul class="p-l-5">
                                        @foreach ($events as $event)
                                            <li>
                                                {{ $event->type->name }}
                                                @if($event->isFree() === false)
                                                    (+{{ $event->displayPrice() }})
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h4>Register a...</h4>
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <a href="#" class="btn btn-success btn-cons">Adult / Family</a>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        @if(Auth::user() !== null)
                                            <a href="/tournaments/{{ $tournament->slug }}/group/choose-teams" class="btn btn-success btn-cons" id="register-group">Group</a>
                                        @else
                                            <button type="button" class="btn btn-success btn-cons" data-toggle="tooltip" data-placement="bottom" title="You must be logged in to register a group">Group</button>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <a href="#" class="btn btn-success btn-cons">Quizmaster</a>
                                    </div>
                                </div>
                                <hr/>
                                {!! $tournament->details !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection