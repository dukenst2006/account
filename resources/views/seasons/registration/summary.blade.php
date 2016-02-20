@extends('layouts.master')

@section('title', 'Registration Summary')

@section('content')
    <div class="content">
        @include('partials.messages')
        <h4>Registration <span class="semi-bold">Summary</span></h4>
        <div class="grid simple">
            <div class="grid-body no-border" style="padding-bottom: 10px; padding-top: 20px">
                @foreach ($registration->programs() as $program)
                    <div class="row">
                        <div class="col-md-6">
                            <h3>{{ $program->name }}</h3>
                        </div>
                        <div class="col-md-6 text-right m-t-5">
                        @if($registration->hasGroup($program) === false)
                            <a href="/register/{{ $program->slug }}/search/group" class="btn btn-primary btn-cons">Join {{ $program->abbreviation }} Group</a>
                        @endif
                        </div>
                    </div>
                    @if($registration->hasGroup($program))
                    <div class="gray-box">
                        @include('seasons.registration.partials.group', [
                            'action'    => 'register',
                            'group'     => $registration->group($program)
                        ])
                    </div>
                    @endif
                    <table class="table no-more-tables">
                        <thead>
                        <tr>
                            <th style="width:30%">Player</th>
                            <th style="width:30%">Grade</th>
                            <th style="width:30%">T-Shirt Size</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($registration->players($program) as $player)
                            <tr>
                                <td>{{ $player->full_name }}</td>
                                <td>{{ \BibleBowl\Presentation\Describer::describeGrade($registration->grade($player->id)) }}</td>
                                <td>{{ \BibleBowl\Presentation\Describer::describeShirtSize($registration->shirtSize($player->id)) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endforeach
                @if ($registration->hasFoundAllGroups())
                <div class="text-center">
                    <a href="/register/submit" class="btn btn-primary btn-cons">Submit Registration</a>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection