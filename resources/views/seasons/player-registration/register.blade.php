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
                                <th style="width:30%">Player</th>
                                <th style="width:30%">Grade</th>
                                <th style="width:30%">T-Shirt Size</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(Auth::user()->players as $index => $player)
                            <tr>
                                <td><div class="inline">{{ $player->full_name }}</div>
                                </td>
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