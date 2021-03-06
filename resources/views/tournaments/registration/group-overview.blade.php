@extends('layouts.master')

@section('title', 'Group Overview - '.$tournament->name)

@section('content')
    <?php $quizmasterCount = count($quizmasters); ?>
    <?php $playersRequiringPayment = 0; ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                @include('tournaments.partials.tournament-summary', [
                    'tournament' => $tournament
                ])
                <div class="grid simple">
                    <div class="grid-body no-border">
                        @if($tournament->shouldWarnAboutTeamLocking() && $tournament->teamsAreLocked() === false)
                            <div class="alert alert-info m-t-10 text-center">
                                You have {{ $tournament->lock_teams->diffForHumans(null, true) }} left to make changes to teams and other player events (if applicable)
                            </div>
                        @endif
                        @if($tournament->isRegistrationClosed())
                            <div class="m-t-15">
                            @include('tournaments.partials.closed-registration', [
                                'tournament' => $tournament
                            ])
                            </div>
                        @endif
                        @if($teamSet == null)
                            <div class="text-center m-b-20">
                                <h3 class="m-t-20 m-b-20">Group Registration</h3>
                                <p>From this page you'll be able to administer your entire group's registration which includes <br/>teams, players, quizmasters, adults and families.</p>
                                <a href="/tournaments/{{ $tournament->slug }}/registration/group/choose-teams" class="btn btn-primary btn-cons">Register Teams</a>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-5 col-sm-5">
                                    <h4 class="pull-left semi-bold">Teams</h4>
                                    <div class="pull-right p-t-10">
                                        @if($teamCount > 0)
                                            <a href="/teamsets/{{ $teamSet->id }}/pdf" class="btn btn-info btn-small"><i class="fa fa-download"></i> PDF</a>
                                        @endif
                                        @if($tournament->isRegistrationOpen())
                                            <a href="/teamsets/{{ $teamSet->id }}" class="btn btn-primary btn-small">Manage Teams</a>
                                        @endif
                                    </div>
                                    <div class="clearfix"></div>
                                    <p></p>
                                    <ul>
                                        <li><strong>{{ number_format($teamCount) }} team{{ $teamCount != 1 ? 's' : '' }}</strong>
                                            @if($tournament->hasFee(\App\ParticipantType::TEAM) && ($teamsRequiringPayment = $teamSet->teams()->unpaid()->count()) > 0)
                                                <span class="text-error">({{ number_format($teamsRequiringPayment) }} require payment)</span>
                                            @endif
                                        </li>
                                        <li>
                                            <strong>{{ number_format($playerCount) }} player{{ $playerCount != 1 ? 's' : '' }}</strong>
                                            @if($tournament->hasFee(\App\ParticipantType::PLAYER) && ($playersRequiringPayment = $teamSet->unpaidPlayers()->count()) > 0)
                                                <span class="text-error">({{ number_format($playersRequiringPayment) }} require payment)</span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6 col-md-offset-1 col-sm-6 col-sm-offset-1">
                                    @if($individualEventCount > 0)
                                    <h4 class="pull-left semi-bold">Optional Events</h4>
                                    <div class="pull-right p-t-10">
                                        @if($tournament->isRegistrationOpen())
                                            <a href="/tournaments/{{ $tournament->slug }}/registration/group/events" class="btn btn-primary btn-small">Manage Participation</a>
                                        @endif
                                    </div>
                                    <div class="clearfix"></div>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th class="text-center">Participating</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $eventPlayersRequiringPayment = 0; ?>
                                        @foreach($tournament->individualEvents()->withOptionalParticipation()->get() as $event)
                                            <?php $eventPlayers = $event->players()->onTeamSet($teamSet)->count(); ?>
                                            <tr>
                                                <td class="v-align-middle">{{ $event->type->name }}</td>
                                                <td class="v-align-middle text-center">
                                                    {{ $eventPlayers }} player{{ $eventPlayers != 1 ? 's' : '' }}
                                                    @if ($event->isFree() === false)
                                                        <?php $eventPlayersRequiringPayment = $event->unpaidPlayers()->count(); ?>
                                                        @if($eventPlayersRequiringPayment > 0)
                                                            <?php $playersRequiringPayment += $eventPlayersRequiringPayment; ?>
                                                        <span class="text-error">({{ number_format($eventPlayersRequiringPayment) }} require payment)</span>
                                                        @endif
                                                    @else

                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @include('partials.messages')
                        @if(
                        $tournament->isRegistrationOpen() && (
                            (isset($teamsRequiringPayment) && $teamsRequiringPayment > 0) ||
                            (isset($playersRequiringPayment) && $playersRequiringPayment > 0) ||
                            (isset($eventPlayersRequiringPayment) && $eventPlayersRequiringPayment > 0) ||
                            ($tournament->hasFee(\App\ParticipantType::FAMILY) && $tournament->spectators()->families()->registeredByHeadCoach()->unpaid()->where('group_id', $group->id)->count() > 0) ||
                            ($tournament->hasFee(\App\ParticipantType::ADULT) && $tournament->spectators()->adults()->registeredByHeadCoach()->unpaid()->where('group_id', $group->id)->count() > 0) ||
                            ($tournament->hasFee(\App\ParticipantType::QUIZMASTER) && $tournament->tournamentQuizmasters()->registeredByHeadCoach()->unpaid()->where('group_id', $group->id)->count() > 0)
                        ))
                        <div class="alert text-center">
                            Portions of your registration require payment before they are complete.<br/>
                            <a href='/tournaments/{{ $tournament->slug }}/registration/group/pay' class="btn btn-warning btn-small btn-cons m-t-10">Pay Fees</a>
                        </div>
                        @endif
                        @if($tournament->registrationIsEnabled(\App\ParticipantType::QUIZMASTER) && ($teamSet != null || ($teamSet == null && count($quizmasters) > 0)))
                            @if($tournament->hasFee(\App\ParticipantType::QUIZMASTER) == false && ($tournament->settings->shouldRequireQuizmastersByGroup() || $tournament->settings->shouldRequireQuizmastersByTeamCount()))
                                @if($tournament->settings->shouldRequireQuizmastersByGroup() && $quizmasterCount < $tournament->settings->quizmastersToRequireByGroup())
                                    <div class="alert alert-error text-center">
                                        You need to register {{ $tournament->settings->quizmastersToRequireByGroup() }} quizmaster(s) before your registration is complete.
                                    </div>
                                @elseif ($tournament->settings->shouldRequireQuizmastersByTeamCount())
                                    <?php
                                    $teamCount = $tournament->teamSet($group)->teams()->count();
                                    $numberOfQuizmastersRequired = $tournament->numberOfQuizmastersRequiredByTeamCount($teamCount);
                                    ?>
                                    @if($quizmasterCount < $numberOfQuizmastersRequired)
                                    <div class="alert alert-error text-center">
                                        Because you have {{ $teamCount }} team(s), you need {{ $numberOfQuizmastersRequired }} quizmaster(s) before your registration is complete.
                                    </div>
                                    @endif
                                @endif
                            @endif
                            <div class="row form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h4 class="semi-bold">Quizmasters</h4>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6 text-right p-t-10">
                                            @if($tournament->isRegistrationOpen())
                                                <a href="/tournaments/{{ $tournament->slug }}/registration/quizmaster" class="btn btn-primary btn-cons btn-small"><i class="fa fa-plus"></i> Add Quizmaster</a>
                                            @endif
                                        </div>
                                    </div>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            @if($tournament->hasFee(\App\ParticipantType::QUIZMASTER))
                                                <th class="text-center">Fees</th>
                                                <th class="text-center hidden-xs">Email</th>
                                            @else
                                                <th class="text-center">Email</th>
                                            @endif
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($quizmasterCount > 0)
                                            @foreach($quizmasters as $quizmaster)
                                                <tr>
                                                    <td class="v-align-middle">{{ $quizmaster->full_name }}</td>
                                                    @if($tournament->hasFee(\App\ParticipantType::QUIZMASTER))
                                                        <td class="v-align-middle text-center">
                                                            @if($quizmaster->hasPaid())
                                                                <span class="text-success">PAID</span>
                                                            @else
                                                                <span class="text-error">PAYMENT DUE</span>
                                                            @endif
                                                        </td>
                                                        <td class="v-align-middle text-center hidden-xs"><a href="mailto:{{ $quizmaster->email }}">{{ $quizmaster->email }}</a></td>
                                                    @else
                                                        <td class="v-align-middle text-center"><a href="mailto:{{ $quizmaster->email }}">{{ $quizmaster->email }}</a></td>
                                                    @endif
                                                    <td class="v-align-middle text-center visible-xs">
                                                    @if($tournament->isRegistrationOpen() && (($tournament->hasFee(\App\ParticipantType::QUIZMASTER) && $quizmaster->hasntPaid()) || $tournament->hasFee(\App\ParticipantType::QUIZMASTER) === false))
                                                        {!! Form::open(['url' => ['/tournaments/'.$tournament->slug.'/registration/quizmaster/'.$quizmaster->guid], 'method' => 'delete']) !!}
                                                        <td class="text-center">
                                                            <button class="btn btn-danger-dark btn-xs btn-mini m-l-10" name="delete-quizmaster-{{ $quizmaster->id }}"><i class="fa fa-trash-o"></i> Delete</button>
                                                        </td>
                                                        {!! Form::close() !!}
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="t-p-20 b-p-20 text-center help" style="font-style:italic">You haven't registered any quizmasters yet</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        @if(($tournament->registrationIsEnabled(\App\ParticipantType::ADULT) || $tournament->registrationIsEnabled(\App\ParticipantType::FAMILY)) && ($teamSet != null || ($teamSet == null && count($spectators) > 0)))
                            <div class="row form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h4 class="semi-bold">Spectators</h4>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6 text-right p-t-10">
                                            @if($tournament->isRegistrationOpen())
                                                <a href="/tournaments/{{ $tournament->slug }}/registration/spectator" class="btn btn-primary btn-cons btn-small"><i class="fa fa-plus"></i> Add Adult/Family</a>
                                            @endif
                                        </div>
                                    </div>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th class="text-center">Family</th>
                                            @if($tournament->hasFee(\App\ParticipantType::ADULT) || $tournament->hasFee(\App\ParticipantType::FAMILY))
                                                <th class="text-center">Fees</th>
                                                <th class="text-center hidden-xs">Email</th>
                                            @else
                                                <th></th>
                                                <th class="text-center">Email</th>
                                            @endif
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($spectators) > 0)
                                            @foreach($spectators as $spectator)
                                                <tr>
                                                    <td class="v-align-middle">{{ $spectator->full_name }}</td>
                                                    <td class="v-align-middle text-center hidden-xs">
                                                        @if($spectator->isFamily())
                                                            @if($spectator->spouse_first_name != null)
                                                                {{ $spectator->spouse_first_name }}
                                                                @if($spectator->minors->count() > 0)
                                                                    +
                                                                @endif
                                                            @endif

                                                            @if($spectator->minors->count() > 0)
                                                                {{ $spectator->minors->count() }} minors
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="v-align-middle text-center visible-xs">
                                                        @if($spectator->isFamily())
                                                            <div class="fa fa-check"></div>
                                                        @endif
                                                    </td>
                                                    <td class="v-align-middle text-center">
                                                        @if(($tournament->hasFee(\App\ParticipantType::ADULT) && $spectator->isAdult()) || ($tournament->hasFee(\App\ParticipantType::FAMILY) && $spectator->isFamily()))
                                                            @if($spectator->hasPaid())
                                                                <span class="text-success">PAID</span>
                                                            @else
                                                                <span class="text-error">PAYMENT DUE</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="v-align-middle text-center hidden-xs"><a href="mailto:{{ $spectator->email }}">{{ $spectator->email }}</a></td>
                                                    <td class="v-align-middle text-center visible-xs">
                                                        @if($tournament->isRegistrationOpen() && (($tournament->hasFee(\App\ParticipantType::ADULT) && $spectator->isAdult() && $spectator->hasntPaid()) || ($tournament->hasFee(\App\ParticipantType::FAMILY) && $spectator->isFamily() && $spectator->hasntPaid()) || ($tournament->hasFee(\App\ParticipantType::FAMILY) === false && $tournament->hasFee(\App\ParticipantType::ADULT) === false)))
                                                            {!! Form::open(['url' => ['/tournaments/'.$tournament->slug.'/registration/spectator/'.$spectator->guid], 'method' => 'delete']) !!}
                                                            <td class="text-center">
                                                                <button class="btn btn-danger-dark btn-xs btn-mini m-l-10" name="delete-spectator-{{ $spectator->id }}"><i class="fa fa-trash-o"></i> Delete</button>
                                                            </td>
                                                            {!! Form::close() !!}
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="t-p-20 b-p-20 text-center help" style="font-style:italic">You haven't registered any spectators yet</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
    </div>
@endsection