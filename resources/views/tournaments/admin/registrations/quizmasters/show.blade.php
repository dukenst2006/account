@extends('layouts.master')

@section('title', $quizmaster->full_name.' | Quizmasters | '.$tournament->name)

@section('content')
    <div class="content">
        <div class="grid simple horizontal-menu">
            <div class="grid-title no-border">
                <h3 class="semi-bold p-t-10 m-l-15 p-b-15" style="margin-bottom: 0">{{ $tournament->name }}</h3>
            </div>
            <div class="bar">
                <div class="bar-inner">
                    @include('tournaments.admin.menu-partial', [
                        'selected' => 'Registrations'
                    ])
                </div>
            </div>
            <div class="grid-body no-border">
                <div class="row m-t-20">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <h5><i class="fa fa-user"></i> <span class="semi-bold">Quizmaster</span></h5>
                        @include('partials.user-contact', [
                            'user' => $quizmaster
                        ])<br/><br/>
                        {!! Html::genderIcon($quizmaster->gender) !!} {!! \App\Presentation\Describer::describeGender($quizmaster->gender) !!}
                        <br/>
                        @if($tournament->settings->shouldCollectShirtSizes())
                            Shirt size: {!! \App\Presentation\Describer::describeShirtSize($quizmaster->shirt_size) !!}
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <h5><i class="fa fa-pencil"></i> Registration <span class="semi-bold">Details</span></h5>
                        @if($tournament->hasFee(\App\ParticipantType::QUIZMASTER))
                            <p class="m-t-10">
                                @if($quizmaster->hasPaid())
                                    <span class="text-success">PAID</span> (<a href="/account/receipts/{{ $quizmaster->receipt_id }}">#{{ $quizmaster->receipt_id }}</a>)
                                @else
                                    <span class="text-error">PAYMENT DUE</span>
                                @endif
                            </p>
                        @endif
                        @if($quizmaster->wasRegisteredByHeadCoach())
                            <p class="m-t-10">
                                Registered by <strong>{{ $quizmaster->registeredBy->full_name }}</strong>
                            </p>
                        @endif
                        @if($quizmaster->hasGroup())
                            <p class="m-t-10">
                                Registered with <strong><a href="/admin/tournaments/{{ $tournament->id }}/registrations/groups/{{ $quizmaster->group->id }}">{{ $quizmaster->group->name }}</a></strong>
                            </p>
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        @if($tournament->settings->shouldCollectQuizmasterPreferences())
                        <h5><i class="fa fa-gear"></i> Quizzing <span class="semi-bold">Preferences</span></h5>
                        <table class="table table-condensed">
                            <tr>
                                <td>Times quizzed at this tournament</td>
                                <td>{{ $quizmaster->quizzing_preferences->quizzedAtThisTournamentBefore() ? 'Yes ('.$quizmaster->quizzing_preferences->timesQuizzedAtThisTournament().' times)' : 'No' }}</td>
                            </tr>
                            <tr>
                                <td>Games quizzed this season</td>
                                <td>{{ number_format($quizmaster->quizzing_preferences->gamesQuizzedThisSeason()) }}</td>
                            </tr>
                            <tr>
                                <td>Quizzing interest (1-3)</td>
                                <td>{{ $quizmaster->quizzing_preferences->quizzingInterest() }}</td>
                            </tr>
                            <tr>
                                <td>Quizzing frequency</td>
                                <td>{{ $quizmaster->quizzing_preferences->quizzingFrequency() }}</td>
                            </tr>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection