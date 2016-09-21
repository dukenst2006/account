@extends('layouts.master')

@section('title', 'Players')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body dataTables_wrapper">
                <form method="get">
                <div class="row">
                    <div class="col-md-6 col-xs-4">
                        <h4 class="semi-bold">Players</h4>
                    </div>
                    <div class="input-group transparent col-md-4 col-md-offset-8 col-xs-8">
                        <input type="text" class="form-control" placeholder="Search by player or guardian name" name="q" value="{{ Input::get('q') }}"/>
                        <span class="input-group-addon">
                            <i class="fa fa-search"></i>
                        </span>
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
                                <td class="text-center hidden-xs">{!! HTML::genderIcon($player->gender) !!}</td>
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