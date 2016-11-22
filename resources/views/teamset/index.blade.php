@extends('layouts.master')

@section('title', 'Teams')

@section('content')
    @include('partials.messages')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body">
                <div class="row">
                    <div class="col-md-8 col-sm-10">
                        <h4 class="semi-bold">Team Sets</h4>
                        <div class="help m-b-10">Create different combinations of teams for varying uses during practice, competition, etc.</div>
                    </div>
                    <div class="col-md-4 col-sm-2 text-right">
                        <a href="/teamsets/create" class="btn btn-primary">New Team Set</a>
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
                                    <a href="/teamsets/{{ $teamSet->id }}/pdf" class="btn btn-info btn-xs btn-mini"><i class="fa fa-download"></i> PDF</a>
                                    <button class="btn btn-danger-dark btn-xs btn-mini m-l-10"><i class="fa fa-trash-o"></i> Delete</button>
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