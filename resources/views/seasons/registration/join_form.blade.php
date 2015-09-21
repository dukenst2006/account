@extends('layouts.master')

@section('title', 'Join '.$group->name)

@section('content')
    <div class="content">
        @include('partials.messages')
        <h4>Join for <span class="semi-bold">{{ Session::season()->name }} Season</span></h4>
        <div class="grid simple">
            <div class="grid-body no-border" style="padding-bottom: 10px; padding-top: 20px">
                @include('seasons.registration.completed-steps.group', [
                    'action'    => 'join',
                    'group'     => $group
                ])
                <div class="row b-t b-grey m-b-15"></div>
                {!! Form::open(['class' => 'form-horizontal', 'role' => 'form']) !!}
                <div class="row">
                    <div class="col-md-2">
                        Players
                    </div>
                    <div class="col-md-10 p-b-10">
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
                            @foreach($players as $player)
                                <tr>
                                    <td>
                                        <div class="checkbox check-default">
                                            {!! Form::checkbox("player[".$player->id."][register]", 1, (Session::hasOldInput() && !Input::has("player[".$player->id."][register]") ? false : true), [ "id" => "player".$player->id.'register' ]) !!}
                                            <label for="player{{ $player->id }}register"></label>
                                        </div>
                                    </td>
                                    <td>{{ $player->full_name }}</td>
                                    <td>{{ \BibleBowl\Presentation\Describer::describeGrade($player->seasons->first()->pivot->grade) }}</td>
                                    <td>{{ \BibleBowl\Presentation\Describer::describeShirtSize($player->seasons->first()->pivot->shirt_size) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary btn-cons" type="submit">Join</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection