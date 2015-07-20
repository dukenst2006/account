@extends('layouts.master')

@section('title', 'Join '.$group->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="grid simple">
                    @include('seasons.registration.partials.group_profile', [
                        'group' => $group
                    ])
                    <div class="grid-title no-border">
                        <h4>Join for <span class="semi-bold">{{ Session::season()->name }} Season</span></h4>
                        <p class="muted">Select the players who will be playing with this group</p>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
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
                            @foreach($players as $player)
                                <tr>
                                    <td>
                                        <div class="checkbox check-default">
                                            {!! Form::checkbox("player[".$player->id."]", 1, (Session::hasOldInput() && !Input::has("player[".$player->id."]") ? false : true), [ "id" => "register-".$player->id ]) !!}
                                            <label for="register-{{ $player->id }}"></label>
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
                    <div class="grid-body no-border">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary btn-cons" type="submit">Join</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection