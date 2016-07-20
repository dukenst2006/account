@extends('layouts.master')

@section('title', 'Registration Summary')

@section('content')
    <div class="content">
        @include('partials.messages')
        <h4>Registration <span class="semi-bold">Summary</span></h4>
        <div class="grid simple">
            <div class="grid-body no-border" style="padding-bottom: 10px; padding-top: 20px">
                <p>Now that we know who's playing, lets find the group for you to play with</p>
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
                    <table class="table no-more-tables m-t-30 m-b-30">
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
                    {!! Form::open(['url' => '/register/submit', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                    <div class="row">
                        <div class="col-md-5 col-md-offset-4 col-sm-6 col-sm-offset-4 m-b-10">
                            <div class="checkbox check-primary">
                                {!! Form::checkbox('terms_of_participation', 1, old('terms_of_participation'), ['id' => 'terms_of_participation']) !!}
                                <label for="terms_of_participation">I have read and agree to the <a href="/terms-of-participation" target="_blank">Terms of Participation</a></label>
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