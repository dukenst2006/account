@extends('layouts.master')

@section('title', 'Player Roster')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Player <span class="semi-bold">Roster</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a href="/roster/export" type="button" class="btn btn-primary btn-cons"><i class="fa fa-download"></i>&nbsp;Download CSV</a>
                            </div>
                        </div>
                        <table class="table no-more-tables">
                            <thead>
                            <tr>
                                <th style="width:20%">Player</th>
                                <th style="width:25%">Guardian</th>
                                <th style="width:5%" class="text-center">Age</th>
                                <th style="width:15%" class="text-center">Grade</th>
                                <th style="width:15%" class="text-center">Shirt Size</th>
                                <th style="width:20%" class="text-center">Options</th>
                            </tr>
                            </thead>
                            <tbody>
                        @if(count($active_players) > 0)
                            @foreach($active_players as $player)
                            <tr>
                                <td class="v-align-middle">{{ $player->full_name }}</td>
                                <td class="v-align-middle">{{ $player->guardian->full_name }}</td>
                                <td class="v-align-middle text-center">{{ $player->age() }}</td>
                                <td class="v-align-middle text-center">{{ \BibleBowl\Presentation\Describer::describeGrade($player->pivot->grade) }}</td>
                                <td class="v-align-middle text-center">{{ $player->pivot->shirt_size }}</td>
                                <td class="v-align-middle text-center">
                                    <a href="/player/{{ $player->id }}/deactivate" id="deactivate-{{ $player->id }}" data-toggle="tooltip" data-placement="left" title="Use this option if this player is no longer participating this season.">Deactivate</a>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="7"><div class="muted m-t-40" style="font-style: italic">No players have registered yet</div></td>
                            </tr>
                        @endif
                            </tbody>
                        </table>

                        @if($inactive_player_count > 0)
                            <div class="text-center"><i><a href="/roster/inactive" id="inactive-roster">{{ $inactive_player_count }} player{{ ($inactive_player_count > 0) ? 's' : '' }}</a> no longer participating this season</i></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection