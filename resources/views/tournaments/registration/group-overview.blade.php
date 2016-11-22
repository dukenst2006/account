@extends('layouts.master')

@section('title', 'Group Overview - '.$tournament->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                @include('tournaments.partials.tournament-summary', [
                    'tournament' => $tournament
                ])
                <div class="grid simple">
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        @if($tournament->shouldWarnAboutTeamLocking())
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
                                <div class="col-md-6">
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
                                            @if($tournament->hasFee(\BibleBowl\ParticipantType::TEAM) && ($teamsRequiringPayment = $teamSet->teams()->unpaid()->count()) > 0)
                                                <span class="text-error">({{ number_format($teamsRequiringPayment) }} require payment)</span>
                                            @endif
                                        </li>
                                        <li>
                                            <strong>{{ number_format($playerCount) }} player{{ $playerCount != 1 ? 's' : '' }}</strong>
                                            @if($tournament->hasFee(\BibleBowl\ParticipantType::PLAYER) && ($playersRequiringPayment = $teamSet->unpaidPlayers()->count()) > 0)
                                                <span class="text-error">({{ number_format($playersRequiringPayment) }} require payment)</span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    @if($individualEventCount > 0)
                                    <h4 class="pull-left semi-bold">Events</h4>
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
                                        @foreach($tournament->individualEvents()->get() as $event)
                                            <?php $eventPlayers = $event->players()->registeredWithGroup(Session::season(), $group)->count(); ?>
                                            <tr>
                                                <td class="v-align-middle">{{ $event->type->name }}</td>
                                                <td class="v-align-middle text-center">
                                                    {{ $eventPlayers }} player{{ $eventPlayers != 1 ? 's' : '' }}
                                                    @if ($event->isFree() === false)
                                                        <?php $playersRequiringPayment = $event->unpaidPlayers()->count(); ?>
                                                        @if($playersRequiringPayment > 0)
                                                            <?php $eventPlayersRequiringPayment += $playersRequiringPayment; ?>
                                                        <span class="text-error">({{ number_format($playersRequiringPayment) }} require payment)</span>
                                                        @endif
                                                    @else
                                                        <?php $playersRequiringPayment = 0; ?>
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
                        @if(
                        $tournament->isRegistrationOpen() && (
                            (isset($teamsRequiringPayment) && $teamsRequiringPayment > 0) ||
                            (isset($playersRequiringPayment) && $playersRequiringPayment > 0) ||
                            (isset($eventPlayersRequiringPayment) && $eventPlayersRequiringPayment > 0) ||
                            ($tournament->hasFee(\BibleBowl\ParticipantType::FAMILY) && $tournament->spectators()->families()->registeredByHeadCoach()->unpaid()->count() > 0) ||
                            ($tournament->hasFee(\BibleBowl\ParticipantType::ADULT) && $tournament->spectators()->adults()->registeredByHeadCoach()->unpaid()->count() > 0) ||
                            ($tournament->hasFee(\BibleBowl\ParticipantType::QUIZMASTER) && $tournament->tournamentQuizmasters()->registeredByHeadCoach()->unpaid()->count() > 0)
                        ))
                        <div class="alert text-center">
                            Portions of your registration require payment before they are complete.<br/>
                            <a href='/tournaments/{{ $tournament->slug }}/registration/group/pay' class="btn btn-warning btn-small btn-cons m-t-10">Pay Fees</a>
                        </div>
                        @endif
                        @if($tournament->registrationIsEnabled(\BibleBowl\ParticipantType::QUIZMASTER) && ($teamSet != null || ($teamSet == null && count($quizmasters) > 0)))
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
                                            @if($tournament->hasFee(\BibleBowl\ParticipantType::QUIZMASTER))
                                                <th class="text-center">Fees</th>
                                                <th class="text-center hidden-xs">Email</th>
                                            @else
                                                <th class="text-center">Email</th>
                                            @endif
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($quizmasters) > 0)
                                            @foreach($quizmasters as $quizmaster)
                                                <tr>
                                                    <td class="v-align-middle">{{ $quizmaster->full_name }}</td>
                                                    @if($tournament->hasFee(\BibleBowl\ParticipantType::QUIZMASTER))
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
                                                    @if($tournament->isRegistrationOpen() && (($tournament->hasFee(\BibleBowl\ParticipantType::QUIZMASTER) && $quizmaster->hasntPaid()) || $tournament->hasFee(\BibleBowl\ParticipantType::QUIZMASTER) === false))
                                                        {!! Form::open(['url' => ['/tournaments/'.$tournament->slug.'/registration/quizmaster/'.$quizmaster->guid], 'method' => 'delete']) !!}
                                                        <td class="text-center">
                                                            <button class="btn btn-danger-dark btn-xs btn-mini m-l-10" name="delete-quizmaster-{{ $quizmaster->id }}"><i class="fa fa-trash-o"></i> Delete</button>
                                                        </td>
                                                        {!! Form::close() !!}
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
                        @if(($tournament->registrationIsEnabled(\BibleBowl\ParticipantType::ADULT) || $tournament->registrationIsEnabled(\BibleBowl\ParticipantType::FAMILY)) && ($teamSet != null || ($teamSet == null && count($spectators) > 0)))
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
                                            @if($tournament->hasFee(\BibleBowl\ParticipantType::ADULT) || $tournament->hasFee(\BibleBowl\ParticipantType::FAMILY))
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
                                                        @if(($tournament->hasFee(\BibleBowl\ParticipantType::ADULT) && $spectator->isAdult()) || ($tournament->hasFee(\BibleBowl\ParticipantType::FAMILY) && $spectator->isFamily()))
                                                            @if($spectator->hasPaid())
                                                                <span class="text-success">PAID</span>
                                                            @else
                                                                <span class="text-error">PAYMENT DUE</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="v-align-middle text-center hidden-xs"><a href="mailto:{{ $spectator->email }}">{{ $spectator->email }}</a></td>
                                                    <td class="v-align-middle text-center visible-xs">
                                                        @if($tournament->isRegistrationOpen() && (($tournament->hasFee(\BibleBowl\ParticipantType::ADULT) && $spectator->isAdult() && $spectator->hasntPaid()) || ($tournament->hasFee(\BibleBowl\ParticipantType::FAMILY) && $spectator->isFamily() && $spectator->hasntPaid()) || ($tournament->hasFee(\BibleBowl\ParticipantType::FAMILY) === false && $tournament->hasFee(\BibleBowl\ParticipantType::ADULT) === false)))
                                                            {!! Form::open(['url' => ['/tournaments/'.$tournament->slug.'/registration/spectator/'.$spectator->guid], 'method' => 'delete']) !!}
                                                            <td class="text-center">
                                                                <button class="btn btn-danger-dark btn-xs btn-mini m-l-10" name="delete-spectator-{{ $spectator->id }}"><i class="fa fa-trash-o"></i> Delete</button>
                                                            </td>
                                                            {!! Form::close() !!}
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