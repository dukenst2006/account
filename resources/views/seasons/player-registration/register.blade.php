@extends('layouts.master')

@section('title', 'Register for '.Session::season()->name.' Season')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Register for <span class="semi-bold">{{ Session::season()->name }} Season</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::open(['class' => 'form-horizontal', 'role' => 'form']) !!}
                        <table class="table no-more-tables">
                            <thead>
                            <tr>
                                <th style="width:10%">
                                    <div class="checkbox check-default">
                                        <input id="checkAllPlayers" type="checkbox" value="1" class="checkall">
                                        <label for="checkAllPlayers"></label>
                                    </div>
                                </th>
                                <th style="width:30%">Player</th>
                                <th style="width:30%">Grade</th>
                                <th style="width:30%">T-Shirt Size</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(Auth::user()->players as $index => $player)
                            <tr>
                                <td>
                                    <div class="checkbox check-default">
                                        <input id="register-{{ $player->id }}" type="checkbox" name="player[{{ $player->id }}][register]" value="1">
                                        <label for="register-{{ $player->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $player->full_name }}</td>
                                <td>{!! Form::selectGrade('player['.$player->id.'][grade]', null, ['class' => 'form-control']) !!}</td>
                                <td>{!! Form::selectShirtSize('player['.$player->id.'][shirt_size]', null, ['class' => 'form-control']) !!}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary btn-cons" type="submit">Register</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection