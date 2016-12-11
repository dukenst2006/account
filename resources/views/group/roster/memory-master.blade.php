@extends('layouts.master')

@section('title', 'Memory Master')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <br/>
                        <h4>Memory <span class="semi-bold">Master</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        <p>Check the players who have completed the Memory Master challenge so we can recognize them.</p>
                        @include('partials.messages')
                        {!! Form::open(['class' => 'form-horizontal', 'role' => 'form']) !!}
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width:20%">
                                    <div class="checkbox check-default">
                                        {!! Form::checkbox('all-players', 1, false, [ 'id' => 'all-players', 'class' => 'checkall' ]) !!}
                                        <label for="all-players"></label>
                                    </div>
                                </th>
                                <th style="width:80%">Player</th>
                            </tr>
                            </thead>
                            <tbody>
                        @if(count($players) > 0)
                            @foreach($players as $player)
                            <tr>
                                <td>
                                    <div class="checkbox check-default">
                                        {!! Form::checkbox("player[".$player->id."]", 1, old('player['.$player->id.']', in_array($player->id, $playersWhoAchieved)), [ "id" => "player".$player->id ]) !!}
                                        <label for="player{{ $player->id }}"></label>
                                    </div>
                                </td>
                                <td class="v-align-middle">{{ $player->last_name }}, {{ $player->first_name }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="7"><div class="muted m-t-40" style="font-style: italic">No players have registered yet</div></td>
                            </tr>
                        @endif
                            </tbody>
                        </table>
                        <div class="text-center">
                            <button class="btn btn-primary btn-cons" type="submit">Save</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection