@extends('layouts.master')

@section('title', $spectator->full_name.' | Spectators | '.$tournament->name)

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
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <h5><i class="fa fa-user"></i> <span class="semi-bold">Quizmaster</span></h5>
                        @include('partials.user-contact', [
                            'user' => $spectator
                        ])<br/>
                        @include('partials.address', [
                            'address' => $spectator->address
                        ])
                        <br/>
                        {!! Html::genderIcon($spectator->gender) !!} {!! \App\Presentation\Describer::describeGender($spectator->gender) !!}
                        <br/>
                        @if($tournament->settings->shouldCollectShirtSizes())
                            Shirt size: {!! \App\Presentation\Describer::describeShirtSize($spectator->shirt_size) !!}<br/>
                        @endif
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        @if($tournament->settings->shouldCollectQuizmasterPreferences())
                            <h5><i class="fa fa-users"></i> Family <span class="semi-bold">Details</span></h5>
                            @if($spectator->hasSpouse())
                                <p class="'m-t-10">
                                    <h4>Spouse</h4>
                                    {{ $spectator->spouse_first_name }}<br/>
                                    {!! Html::genderIcon($spectator->gender) !!} {!! \App\Presentation\Describer::describeGender($spectator->spouse_gender) !!}<br/>
                                    @if($tournament->settings->shouldCollectShirtSizes())
                                        Shirt size: {!! \App\Presentation\Describer::describeShirtSize($spectator->spouse_shirt_size) !!}
                                    @endif
                                </p>
                            @endif

                            @if($spectator->isFamily())
                                    <h4>Children</h4>
                            <table class="table table-condensed">
                                <tr>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    @if($tournament->settings->shouldCollectShirtSizes())
                                    <th>Shirt Size</th>
                                    @endif
                                </tr>
                                @foreach($spectator->minors as $minor)
                                <tr>
                                    <td>{{ $minor->name }}</td>
                                    <td class="text-center">{!! Html::genderIcon($spectator->gender) !!} {!! \App\Presentation\Describer::describeGender($spectator->spouse_gender) !!}</td>
                                    <td class="text-center">{{ $minor->age }}</td>
                                    @if($tournament->settings->shouldCollectShirtSizes())
                                    <td class="text-center">{!! $spectator->shirt_size !!}</td>
                                    @endif
                                </tr>
                                @endforeach
                            </table>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <h5><i class="fa fa-pencil"></i> Registration <span class="semi-bold">Details</span></h5>
                        @if(($tournament->hasFee(\App\ParticipantType::ADULT) && $spectator->isAdult()) || ($tournament->hasFee(\App\ParticipantType::FAMILY) && $spectator->isFamily()))
                            <p class="m-t-10">
                                @if($spectator->hasPaid())
                                    <span class="text-success">PAID</span> (<a href="/account/receipts/{{ $spectator->receipt_id }}">#{{ $spectator->receipt_id }}</a>)
                                @else
                                    <span class="text-error">PAYMENT DUE</span>
                                @endif
                            </p>
                        @endif
                        @if($spectator->wasRegisteredByHeadCoach())
                            <p class="m-t-10">
                                Registered by <strong>{{ $spectator->registeredBy->full_name }}</strong>
                            </p>
                        @endif
                        @if($spectator->hasGroup())
                            <p class="m-t-10">
                                Registered with <strong><a href="/admin/tournaments/{{ $tournament->id }}/registrations/groups/{{ $spectator->group->id }}">{{ $spectator->group->name }}</a></strong>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection