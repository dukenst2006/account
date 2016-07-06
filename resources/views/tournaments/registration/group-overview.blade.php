@extends('layouts.master')

@section('title', 'Choose Teams - '.$tournament->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Group <span class="semi-bold">Registration Overview</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        @include('tournaments.partials.tournament-summary', [
                            'tournament' => $tournament
                        ])
                        <div class="row form-group">
                        @if($tournament->registrationIsEnabled(\BibleBowl\ParticipantType::QUIZMASTER))
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3>Quizmasters</h3>
                                </div>
                                <div class="col-md-6 text-right p-t-10  ">
                                    <a href="/tournaments/{{ $tournament->slug }}/registration/quizmaster" class="btn btn-primary btn-cons btn-small"><i class="fa fa-plus"></i> Add Quizmaster</a>
                                </div>
                            </div>
                            <table class="table no-more-tables">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Gender</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($quizmasters) > 0)
                                    @foreach($quizmasters as $quizmaster)
                                        <tr>
                                            <td class="v-align-middle">{{ $quizmaster->full_name }}</td>
                                            <td class="v-align-middle text-center"><a href="mailto:{{ $quizmaster->email }}">{{ $quizmaster->email }}</a></td>
                                            <td class="v-align-middle text-center">{!! HTML::genderIcon($quizmaster->gender) !!}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="t-p-20 b-p-20 text-center help" style="font-style:italic">You haven't registered any quizmasters yet</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection