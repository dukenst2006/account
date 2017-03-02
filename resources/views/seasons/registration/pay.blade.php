@extends('layouts.master')

@section('title', 'Payment Information')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include('partials.messages')
                <div class="grid simple">
                    <div class="grid-body no-border" style="padding-bottom: 10px; padding-top: 20px">
                        <h4>Select <span class="semi-bold">Players</span></h4>
                        <p>Choose the players you'd like to submit registration payment for.</p>
                        {!! Form::open(['class' => 'form-horizontal', 'role' => 'form']) !!}
                        <div class="row">
                            <div class="col-md-12 p-t-10">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width:10%">
                                            <div class="checkbox check-default">
                                                {!! Form::checkbox('all-players', 1, old('all-players', true), [ 'id' => 'all-players', 'class' => 'checkall' ]) !!}
                                                <label for="all-players"></label>
                                            </div>
                                        </th>
                                        <th style="width:30%">Player</th>
                                        <th style="width:30%">Grade</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($players as $player)
                                        <tr>
                                            <td>
                                                <div class="checkbox check-default">
                                                    {!! Form::checkbox("player[".$player->id."]", 1, old('player['.$player->id.']', true), [ "id" => "player".$player->id ]) !!}
                                                    <label for="player{{ $player->id }}"></label>
                                                </div>
                                            </td>
                                            <td>{{ $player->full_name }}</td>
                                            <td>{{ \App\Presentation\Describer::describeGrade($player->seasons->first()->pivot->grade) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary btn-cons" type="submit">Continue</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection