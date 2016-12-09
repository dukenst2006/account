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
                        @if($tournament->isInactive())
                            <div class="alert text-center">This tournament won't be publicly visible until it is made active.</div>
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
                            <div class="col-md-8 col-sm-8">
                                @include('partials.messages')
                                <h4>Register a...</h4>
                                @if($tournament->isRegistrationOpen())
                                <div class="row m-t-15">
                                    @if($tournament->registrationIsEnabled(\BibleBowl\ParticipantType::ADULT) || $tournament->registrationIsEnabled(\BibleBowl\ParticipantType::FAMILY))
                                        <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                            <?php $spectatorLabel = '<span class="semi-bold">Adult / Family</span><br/><small>Just wanting to spectate</small>'; ?>
                                            @if(Auth::user() !== null && $tournament->isRegisteredAsSpectator(Auth::user()))
                                                <button type="button" class="btn btn-success btn-cons" data-toggle="tooltip" data-placement="bottom" title="You're already registered as a spectator">{!! $spectatorLabel !!}</button>
                                            @else
                                                <a href="/tournaments/{{ $tournament->slug }}/registration/spectator" class="btn btn-success btn-cons" id="register-spectator">{!! $spectatorLabel !!}</a>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                        <?php $groupLabel = '<span class="semi-bold">Group</span><br/><small>Coaches, teams, players, etc.</small>'; ?>
                                        @if(Auth::user() !== null)
                                            @if(Auth::user()->isA(\BibleBowl\Role::HEAD_COACH))
                                                <a href="/tournaments/{{ $tournament->slug }}/group" class="btn btn-success btn-cons" id="register-group">{!! $groupLabel !!}</a>
                                            @else
                                                <a href="#" title="Only head coaches can register their groups" type="button" class="btn btn-success btn-cons" data-toggle="tooltip" data-placement="bottom" id="register-group">{!! $groupLabel !!}</a>
                                            @endif
                                        @else
                                                <a href="#" title="You must be logged in to register a group" type="button" class="btn btn-success btn-cons" data-toggle="tooltip" data-placement="bottom" id="register-group">{!! $groupLabel !!}</a>
                                        @endif
                                    </div>
                                    @if($tournament->registrationIsEnabled(\BibleBowl\ParticipantType::QUIZMASTER))
                                    <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                        <?php $quizmasterLabel = '<span class="semi-bold">Quizmaster</span><br/><small>To quiz the rounds</small>'; ?>
                                        @if(Auth::user() !== null)
                                            @if($tournament->isRegisteredAsQuizmaster(Auth::user()))
                                                <a href="#" class="btn btn-success btn-cons" data-toggle="tooltip" data-placement="bottom" title="You're already registered as a quizmaster" id="register-quizmaster">{!! $quizmasterLabel !!}</a>
                                            @else
                                                <a href="/tournaments/{{ $tournament->slug }}/registration/quizmaster" class="btn btn-success btn-cons" id="register-quizmaster">{!! $quizmasterLabel !!}</a>
                                            @endif
                                        @else
                                                <a href="#" class="btn btn-success btn-cons" data-toggle="tooltip" data-placement="bottom" title="You must be logged in to register as a quizmaster" id="register-quizmaster">{!! $quizmasterLabel !!}</a>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                @else
                                    @include('tournaments.partials.closed-registration', [
                                        'tournament' => $tournament
                                    ])
                                @endif
                                <hr/>
                                {!! $tournament->details !!}

                                @if($tournament->hasAnyParticipantFees())
                                <h3 class="m-t-40">Registration Fees</h3>
                                <hr/>
                                <?php
                                    $allowsOnSiteRegistration = $tournament->allowsOnSiteRegistration();
                                    $hasEarlyBirdRegistration = $tournament->hasEarlyBirdRegistration();

                                    if ($tournament->settings->shouldRequireQuizmastersByGroup()) {
                                        $quizmasterRegistrationNotice = 'Groups registering teams are required to register '.$tournament->settings->quizmastersToRequireByGroup().' quizmaster';
                                        if ($tournament->settings->quizmastersToRequireByGroup() > 1) {
                                            $quizmasterRegistrationNotice .= 's';
                                        }
                                    } elseif ($tournament->settings->shouldRequireQuizmastersByTeamCount()) {
                                        $quizmasterRegistrationNotice = 'Groups registering teams are required to register '.$tournament->settings->quizmastersToRequireByTeamCount().' quizmaster';
                                        if ($tournament->settings->quizmastersToRequireByTeamCount() > 1) {
                                            $quizmasterRegistrationNotice .= 's';
                                        }
                                        $quizmasterRegistrationNotice .= ' for every '.$tournament->settings->teamCountToRequireQuizmastersBy().' team';
                                        if ($tournament->settings->teamCountToRequireQuizmastersBy() > 1) {
                                            $quizmasterRegistrationNotice .= 's';
                                        }
                                    }
                                ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width:30%"></th>
                                        @if($hasEarlyBirdRegistration)
                                            <th class='text-center' style="width:25%">
                                                Early Bird
                                                <br/>(ends {{ $tournament->earlybird_ends->format('M j, Y') }})
                                            </th>
                                        @endif
                                        <th class='text-center' style="width:15%">Registration Fee</th>
                                        @if($allowsOnSiteRegistration)
                                        <th class='text-center' style="width:15%">On-site Fee</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($participantFees as $fee)
                                        <tr>
                                            <td>
                                                <strong>
                                                    {{ $fee->participantType->name }}
                                                    @if($fee->participant_type_id == \BibleBowl\ParticipantType::TEAM && isset($quizmasterRegistrationNotice))
                                                        *
                                                    @endif
                                                </strong>
                                                <div class="muted">{{ $fee->participantType->description }}</div>
                                            </td>
                                            @if($hasEarlyBirdRegistration)
                                                <td class="text-center">
                                                    @if($fee->hasEarlybirdFee())
                                                        ${{ $fee->earlybird_fee }}
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                @if($fee->requiresRegistration())
                                                    @if($fee->hasFee())
                                                        ${{ $fee->fee }}
                                                    @endif
                                                @endif
                                            </td>
                                            @if($allowsOnSiteRegistration)
                                                <td class="text-center">
                                                    @if($fee->hasOnsiteFee())
                                                        ${{ $fee->onsite_fee }}
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                    @if(isset($quizmasterRegistrationNotice))
                                        <p>* {{ $quizmasterRegistrationNotice }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection