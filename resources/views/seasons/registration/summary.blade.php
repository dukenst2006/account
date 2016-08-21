@extends('layouts.master')

@section('title', 'Registration Summary')

@section('content')
    <?php
        $playerNames = [];
    ?>
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
                    <table class="table m-t-30 m-b-30">
                        <thead>
                        <tr>
                            <th>Player</th>
                            <th class="text-center hidden-sm hidden-xs">Grade</th>
                            <th class="text-center visible-sm visible-xs">Grade</th>
                            <th class="text-center hidden-sm hidden-xs">T-Shirt Size</th>
                            <th class="text-center visible-sm visible-xs">T-Shirt Size</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($registration->players($program) as $player)
                            <?php $playerNames[] = $player->full_name ?>
                            <tr>
                                <td>{{ $player->full_name }}</td>
                                <td class="text-center hidden-sm hidden-xs">{{ \BibleBowl\Presentation\Describer::describeGrade($registration->grade($player->id)) }}</td>
                                <td class="text-center visible-sm visible-xs">{{ \BibleBowl\Presentation\Describer::describeGradeShort($registration->grade($player->id)) }}</td>
                                <td class="text-center hidden-sm hidden-xs">{{ \BibleBowl\Presentation\Describer::describeShirtSize($registration->shirtSize($player->id)) }}</td>
                                <td class="text-center visible-sm visible-xs">{{ $registration->shirtSize($player->id) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endforeach
                @if ($registration->hasFoundAllGroups())
                    {!! Form::open(['url' => '/register/submit', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12 b-t b-r b-b b-l b-grey p-b-10 p-r-10 p-l-10 m-b-15" style="height: 150px;text-align: justify; overflow: scroll;">
                            @include('seasons.registration.partials.terms-of-participation', [
                                'user'          => Auth::user(),
                                'season'        => $season,
                                'playerNames'   => $playerNames
                            ])
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 col-md-offset-4 col-sm-6 col-sm-offset-4 m-b-10">
                            <div class="checkbox check-primary">
                                {!! Form::checkbox('terms_of_participation', 1, old('terms_of_participation'), ['id' => 'terms_of_participation']) !!}
                                <label for="terms_of_participation">I have read and agree to the above terms</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-cons">Submit Registration</button>
                    </div>
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>
@endsection