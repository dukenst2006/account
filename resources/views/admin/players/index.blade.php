@extends('layouts.master')

@section('title', 'Players')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body dataTables_wrapper">
                <form method="get">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="semi-bold">Players</h4>
                    </div>
                    <div class="input-group transparent col-md-4 col-md-offset-8">
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
                            <th>Name</th>
                            <th class="text-center">Gender</th>
                            <th class="text-center">Age</th>
                            <th class="text-center">Parent/Guardian</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($players) > 0)
                        @foreach ($players as $player)
                            <tr>
                                <td>
                                    <a href="/admin/players/{{ $player->id }}" class="semi-bold">{{ $player->full_name }}</a>
                                </td>
                                <td class="text-center">{!! HTML::genderIcon($player->gender) !!}</td>
                                <td class="text-center">{{ $player->age() }}</td>
                                <td class="text-center"><a href="/admin/users/{{ $player->guardian_id }}">{{ $player->guardian->full_name }}</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                {!! HTML::pagination($players) !!}
            </div>
        </div>
    </div>
@endsection