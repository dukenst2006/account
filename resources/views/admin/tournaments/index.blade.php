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
                            <th class="col-md-4 text-center">Season</th>
                            <th class="col-md-4 text-center">Dates</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($tournaments) > 0)
                        @foreach ($tournaments as $tournament)
                            <tr>
                                <td>
                                    <a href="/admin/tournaments/{{ $tournament->id }}" class="semi-bold">{{ $tournament->name }}</a><br/>

                                </td>
                                <td class="text-center">{{ $tournament->season->name }}</td>
                                <td class="text-center">{{ $tournament->dateSpan() }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                {!! HTML::pagination($tournaments) !!}
            </div>
        </div>
    </div>
@endsection