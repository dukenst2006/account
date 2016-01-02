@extends('layouts.master')

@section('title', 'Teams')

@section('content')
    @include('partials.messages')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="semi-bold">Teams</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="/teamsets/create" class="btn btn-primary">New Teams</a>
                    </div>
                </div>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th class="col-md-4">Name</th>
                            <th class="col-md-4 text-center">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($teamSets) > 0)
                        @foreach ($teamSets as $teamSet)
                            <tr>
                                <td style="vertical-align: middle">
                                    <a href="/teamsets/{{ $teamSet->id }}" class="semi-bold">{{ $teamSet->name }}</a>
                                </td>
                                {!! Form::open(['action' => ['Teams\TeamSetController@destroy', $teamSet->id], 'method' => 'delete']) !!}
                                <td class="text-center">
                                    <a href="/teamsets/{{ $teamSet->id }}/pdf" class="btn btn-white btn-xs btn-mini"><i class="fa fa-download"></i> PDF</a>
                                    <button class="btn btn-white btn-xs btn-mini"><i class="fa fa-trash-o"></i> Delete</button>
                                </td>
                                {!! Form::close() !!}
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection