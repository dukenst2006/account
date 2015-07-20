@extends('layouts.master')

@section('title', 'Register for '.Session::season()->name.' Season')

@section('includeJs')
    <script src="/assets/js/register.js" type="text/javascript"></script>
@endsection

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="grid simple">
                @if(is_null($group) === false)
                    @include('seasons.registration.partials.group_profile')
                @endif
                    <div class="grid-title no-border">
                        <h4>Register for <span class="semi-bold">{{ Session::season()->name }} Season</span></h4>
                        <p class="muted">Select the players you'd like to register</p>
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
                            @foreach($players as $player)<tr>
                                <td>
                                    <div class="checkbox check-default">
                                        {!! Form::checkbox("player[".$player->id."][register]", 1, (Session::hasOldInput() && !Input::has("player[".$player->id."][register]") ? false : true), [ "id" => "register-".$player->id ]) !!}
                                        <label for="register-{{ $player->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $player->full_name }}</td>
                                <td>{!! Form::selectGrade('player['.$player->id.'][grade]', null, ['class' => 'form-control']) !!}</td>
                                <td>{!! Form::selectShirtSize('player['.$player->id.'][shirtSize]', null, ['class' => 'form-control']) !!}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="grid-title no-border">
                        <h4>Payment</h4>
                        <p>@todo Add some kind of calculation to show total due</p>
                    </div>
                    <div class="grid-body no-border">
                        {{--@include('billing.credit-card-form')--}}
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