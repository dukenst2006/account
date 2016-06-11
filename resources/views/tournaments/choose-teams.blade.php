@extends('layouts.master')

@section('title', 'Choose Teams - '.$tournament->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Choose <span class="semi-bold">Your Teams</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('tournaments.partials.tournament-summary', [
                            'tournament' => $tournament
                        ])
                        <div class="row p-t-10">
                            <div class="col-md-3">
                                Teams:
                            </div>
                            <div class="col-md-9">
                                @if(count($teamSets) > 0)
                                    @foreach($teamSets as $teamSet)
                                        <?php
                                        $teamCount = $teamSet->teams()->count();
                                        $playerCount = $teamSet->players()->count();
                                        // @todo conditional bottom border for non-last rows
                                        ?>
                                        <div class="row p-b-15 b-b b-grey">
                                            <div class="col-md-5">
                                                <strong>{{ $teamSet->name }}</strong>
                                                <div class="help">{{ $playerCount }} players on {{ $teamCount }} team<?=($teamCount > 1 ? 's':'')?></div>
                                            </div>
                                            <div class="col-md-2 text-center p-t-10">
                                                <a href="/teamsets/{{ $teamSet->id }}/pdf" class="btn btn-white btn-xs btn-mini" target="_blank"><i class="fa fa-download"></i> Preview</a>
                                            </div>
                                            <div class="col-md-2 text-center p-t-10">
                                                <a href="/tournaments/{{ $tournament->slug }}/group/teams/{{ $teamSet->id }}" class="btn btn-primary btn-sm btn-small">Use these teams</a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="help p-t-20 text-center" style="font-style: italic">You haven't created any teams yet</div>
                                @endif
                                <div class="text-center p-t-30"><a href="/teamsets/create" class="btn btn-info btn-sm btn-small">Create new teams</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection