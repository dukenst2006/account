@extends('layouts.master')

@section('title', 'Choose Players')

@section('content')
    <div class="content">
        @include('partials.messages')
        <h4>Choose <span class="semi-bold">Players to Register</span></h4>
        <div class="grid simple">
            <div class="grid-body no-border" style="padding-bottom: 10px; padding-top: 20px">
                {!! Form::open(['class' => 'form-horizontal', 'role' => 'form']) !!}
                <table class="table no-more-tables">
                    <thead>
                    <tr>
                        <th style="width:10%"></th>
                        <th style="width:30%">Player</th>
                        <th style="width:30%">Grade</th>
                        <th style="width:30%">T-Shirt Size</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($players as $player)<tr>
                        <td>
                            <div class="checkbox check-default">
                                {!! Form::checkbox("player[".$player->id."][register]", 1, old("player[".$player->id."][register]", true), [ "id" => 'player'.$player->id.'register' ]) !!}
                                <label for="player{{ $player->id }}register"></label>
                            </div>
                        </td>
                        <td>{{ $player->full_name }}</td>
                        <td>{!! Form::selectGrade('player['.$player->id.'][grade]', null, ['class' => 'form-control', 'id' => 'player'.$player->id.'grade']) !!}</td>
                        <td>{!! Form::selectShirtSize('player['.$player->id.'][shirtSize]', null, ['class' => 'form-control', 'id' => 'player'.$player->id.'shirtSize']) !!}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center">
                    <button class="btn btn-primary btn-cons" type="submit">Continue</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection