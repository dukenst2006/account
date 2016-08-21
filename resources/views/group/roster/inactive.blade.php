@extends('layouts.master')

@section('title', 'Player Roster')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Inactive <span class="semi-bold">Players</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        <table class="table">
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
                            @foreach($inactive_players as $player)
                            <tr>
                                <td class="v-align-middle">{{ $player->full_name }}</td>
                                <td class="v-align-middle">{{ $player->guardian->full_name }}</td>
                                <td class="v-align-middle text-center">{{ $player->age() }}</td>
                                <td class="v-align-middle text-center">{{ \BibleBowl\Presentation\Describer::describeGrade($player->pivot->grade) }}</td>
                                <td class="v-align-middle text-center">{{ $player->pivot->shirt_size }}</td>
                                <td class="v-align-middle text-center">
                                    <a href="/player/{{ $player->id }}/activate" id="activate-{{ $player->id }}">Activate</a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection