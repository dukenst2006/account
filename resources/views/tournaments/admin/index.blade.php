@extends('layouts.master')

@section('title', 'Tournaments')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body dataTables_wrapper">
                <form method="get">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="semi-bold">Tournaments</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="/admin/tournaments/create" class="btn btn-primary">New Tournament</a>
                    </div>
                </div>
                </form>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th class="col-md-4">Name</th>
                            <th class="col-md-2 text-center">Status</th>
                            <th class="col-md-4 text-center">Dates</th>
                            <th class="col-md-4 text-center">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($tournaments) > 0)
                        @foreach ($tournaments as $tournament)
                            <tr>
                                <td>
                                    <a href="/admin/tournaments/{{ $tournament->id }}" class="semi-bold">{{ $tournament->name }}</a><br/>
                                </td>
                                <td class="text-center">
                                    @if($tournament->active)
                                        <span class="text-success">Active</span>
                                    @else
                                        <span class="text-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $tournament->dateSpan() }}</td>
                                <td class="text-center">
                                    @if($tournament->active)
                                        <a href="/tournaments/{{ $tournament->slug }}" class="btn btn-white btn-xs btn-mini"><i class="fa fa-external-link"></i> Shareable Link</a>
                                    @endif
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