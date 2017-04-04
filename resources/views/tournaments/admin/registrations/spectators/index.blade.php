@extends('layouts.master')

@section('title', 'Spectators | '.$tournament->name)

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
                    <div class="col-md-6 col-xs-4">
                        <h4 class="semi-bold">Spectators</h4>
                    </div>
                    <div class="col-md-6 col-xs-8 text-right p-t-10">
                        <a class="btn btn-info btn-xs btn-small" href="/admin/tournaments/{{ $tournament->id }}/participants/spectators/export/csv">
                            <i class="fa fa-download"></i>
                            All Eligible Spectators
                        </a>
                    </div>
                </div>
                <form method="get">
                <div class="text-right input-group transparent m-t-20 col-md-4 col-md-offset-8 col-xs-8">
                    <input type="text" class="form-control" placeholder="Search by name or email" name="q" value="{{ Input::get('q') }}"/>
                    <span class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
                </form>
                <table class="table table-condensed m-t-20">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Family</th>
                            @if($tournament->hasFee(\App\ParticipantType::ADULT) || $tournament->hasFee(\App\ParticipantType::FAMILY))
                                <th class="text-center">Fees</th>
                                <th class="text-center hidden-xs">Email</th>
                            @else
                                <th></th>
                                <th class="text-center">Email</th>
                            @endif
                            <th class="text-center hidden-xs">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($spectators) > 0)
                        @foreach ($spectators as $spectator)
                            <tr>
                                <td>
                                    <a href="/admin/tournaments/{{ $tournament->id }}/registrations/spectators/{{ $spectator->id }}" class="semi-bold">{{ $spectator->full_name }}</a><br/>
                                </td>
                                <td class="v-align-middle text-center hidden-xs">
                                    @if($spectator->isFamily())
                                        @if($spectator->spouse_first_name != null)
                                            {{ $spectator->spouse_first_name }}
                                            @if($spectator->minors->count() > 0)
                                                +
                                            @endif
                                        @endif

                                        @if($spectator->minors->count() > 0)
                                            {{ $spectator->minors->count() }} minors
                                        @endif
                                    @endif
                                </td>
                                <td class="v-align-middle text-center visible-xs">
                                    @if($spectator->isFamily())
                                        <div class="fa fa-check"></div>
                                    @endif
                                </td>
                                <td class="v-align-middle text-center">
                                    @if(($tournament->hasFee(\App\ParticipantType::ADULT) && $spectator->isAdult()) || ($tournament->hasFee(\App\ParticipantType::FAMILY) && $spectator->isFamily()))
                                        @if($spectator->hasPaid())
                                            <span class="text-success">PAID</span>
                                        @else
                                            <span class="text-error">PAYMENT DUE</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="v-align-middle text-center hidden-xs"><a href="mailto:{{ $spectator->email }}">{{ $spectator->email }}</a></td>
                                <td class="v-align-middle text-center"><a href="tel:{{ $spectator->phone }}">{{ Html::formatPhone($spectator->phone) }}</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <div class="text-center">
                    {{ $spectators->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection