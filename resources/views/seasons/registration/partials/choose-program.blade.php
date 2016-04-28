@extends('layouts.master')

@section('title', 'Choose Program')

@section('content')
    <div class="content">
        @include('partials.messages')
        <h4>Choose <span class="semi-bold">Program</span></h4>
        <div class="grid simple">
            <div class="grid-body no-border" style="padding-bottom: 10px; padding-top: 20px">
                {!! Form::open(['class' => 'form-horizontal', 'role' => 'form']) !!}
                <p>The below players are eligible to participate in more than one program.  Please choose the program you'd like them to participate in.</p>
                <table class="table no-more-tables">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Grade</th>
                        <th>Program</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($players as $player)
                    <tr>
                        <td>{{ $player->full_name }}</td>
                        <td>{{ BibleBowl\Presentation\Describer::describeGrade($registration->grade($player->id)) }}</td>
                        <td>{!! Form::select('player['.$player->id.']', $programs->pluck('name', 'id'), old('player['.$player->id.']', \BibleBowl\Program::TEEN), ['class' => 'form-control']) !!}</td>
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