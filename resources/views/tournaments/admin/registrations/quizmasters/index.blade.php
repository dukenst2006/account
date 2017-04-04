@extends('layouts.master')

@section('title', 'Quizmasters | '.$tournament->name)

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
                        <h4 class="semi-bold">Quizmasters</h4>
                    </div>
                    <div class="col-md-6 col-xs-8 text-right p-t-10">
                        <a class="btn btn-info btn-xs btn-small" href="/admin/tournaments/{{ $tournament->id }}/participants/quizmasters/export/csv">
                            <i class="fa fa-download"></i>
                            All Eligible Quizmasters
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
                            <th class="col-md-3">Name</th>
                            @if($tournament->hasFee(\App\ParticipantType::QUIZMASTER))
                                <th class="text-center">Fees</th>
                            @endif
                            <th class="col-md-3 text-center hidden-xs">Email</th>
                            <th class="col-md-3 text-center hidden-xs">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($quizmasters) > 0)
                        @foreach ($quizmasters as $quizmaster)
                            <tr>
                                <td>
                                    <a href="/admin/tournaments/{{ $tournament->id }}/registrations/quizmasters/{{ $quizmaster->id }}" class="semi-bold">{{ $quizmaster->full_name }}</a><br/>
                                </td>
                                @if($tournament->hasFee(\App\ParticipantType::QUIZMASTER))
                                    <td class="v-align-middle text-center">
                                        @if($quizmaster->hasPaid())
                                            <span class="text-success">PAID</span> (<a href="/account/receipts/{{ $quizmaster->receipt_id }}">#{{ $quizmaster->receipt_id }}</a>)
                                        @else
                                            <span class="text-error">PAYMENT DUE</span>
                                        @endif
                                    </td>
                                @endif
                                <td class="v-align-middle text-center"><a href="mailto:{{ $quizmaster->email }}">{{ $quizmaster->email }}</a></td>
                                <td class="v-align-middle text-center"><a href="tel:{{ $quizmaster->phone }}">{{ Html::formatPhone($quizmaster->phone) }}</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <div class="text-center">
                    {{ $quizmasters->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection