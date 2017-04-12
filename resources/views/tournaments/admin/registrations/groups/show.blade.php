@extends('layouts.master')

@section('title', $group->name.' | Groups | '.$tournament->name)

@section('content')
    <div class="content">
        <div class="grid simple horizontal-menu">
            <div class="grid-title no-border">
                <h3 class="semi-bold p-t-10 m-l-15 p-b-15" style="margin-bottom: 0">{{ $tournament->name }}</h3>
            </div>
            <div class="bar">
                <div class="bar-inner">
                    @include('tournaments.admin.menu-partial', [
                        'selected' => 'Registrations'
                    ])
                </div>
            </div>
            <div class="grid-body no-border">
                <div class="row m-t-20">
                    <div class="col-md-6">
                        <h5><i class="fa fa-user"></i> <span class="semi-bold">Group</span></h5>
                        {{ $group->name }}<br/><br/>
                        @include('partials.address', [
                            'address' => $group->address
                        ])
                    </div>
                    <div class="col-md-6">
                        @if($teamSet != null)
                        <h5><i class="fa fa-users"></i> Teams</h5>
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
                        <a href="/teamsets/{{ $teamSet->id }}/pdf" class="btn btn-info btn-small"><i class="fa fa-download"></i> PDF</a>
                        @endif
                    </div>
                </div>
                <div class="row m-t-20">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h5><i class="fa fa-users"></i> Quizmasters</h5>
                        @if(count($quizmasters) > 0)
                        <ul>
                            @foreach ($quizmasters as $quizmaster)
                                <li><a href="/admin/tournaments/{{ $tournament->id }}/registrations/groups/{{ $quizmaster->id }}">{{ $quizmaster->full_name }}</a></li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h5><i class="fa fa-users"></i> Spectators</h5>
                        @if(count($spectators) > 0)
                            <ul>
                                @foreach ($spectators as $spectator)
                                    <li><a href="/admin/tournaments/{{ $tournament->id }}/registrations/spectators/{{ $spectator->id }}">{{ $spectator->full_name }}
                                        @if($spectator->isFamily())
                                            & Family
                                        @endif
                                        </a></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection