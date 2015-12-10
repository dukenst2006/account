@extends('layouts.master')

@section('title', 'Teams')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="semi-bold">Teams</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="/team/create" class="btn btn-primary">New Teams</a>
                    </div>
                </div>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th class="col-md-4">Name</th>
                            <th class="col-md-4">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($teamSets) > 0)
                        @foreach ($teamSets as $teamSet)
                            <tr>
                                <td>
                                    <a href="/team/{{ $teamSet->id }}" class="semi-bold">{{ $teamSet->name }}</a>
                                </td>
                                <td>
                                    <a href="/team/{{ $teamSet->id }}/pdf" class="fa fa-download"></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection