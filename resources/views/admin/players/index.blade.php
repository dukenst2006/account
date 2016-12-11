@extends('layouts.master')

@section('title', 'Players')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body dataTables_wrapper">
                <div class="row">
                    <div class="col-md-6 col-xs-4">
                        <h4 class="semi-bold">Players</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="/admin/players/export/csv" class="btn btn-info btn-sm btn-small">Export All</a>
                    </div>
                </div>
                <form method="get">
                <div class="row">
                    <div class="col-md-6 col-sm-6 text-right col-md-offset-6 col-sm-offset-6">
                        <div class="input-group transparent">
                            <input type="text" class="form-control" placeholder="Search by player or guardian name" name="q" value="{{ Input::get('q') }}" autofocus/>
                            <span class="input-group-addon">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
                </form>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th class="col-md-4">Name</th>
                            <th class="col-md-4 text-center hidden-xs">Gender</th>
                            <th class="col-md-4 text-center">Age</th>
                            <th class="col-md-4 text-center">Parent/Guardian</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($players) > 0)
                        @foreach ($players as $player)
                            <tr>
                                <td>
                                    <a href="/admin/players/{{ $player->id }}" class="semi-bold">{{ $player->last_name }}, {{ $player->first_name }}</a>
                                </td>
                                <td class="text-center hidden-xs">{!! Html::genderIcon($player->gender) !!}</td>
                                <td class="text-center">{{ $player->age() }}</td>
                                <td class="text-center"><a href="/admin/users/{{ $player->guardian_id }}">{{ $player->guardian->full_name }}</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                {!! $players->links() !!}
            </div>
        </div>
    </div>
@endsection