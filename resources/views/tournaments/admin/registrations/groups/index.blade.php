@extends('layouts.master')

@section('title', 'Groups | '.$tournament->name)

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
                        <h4 class="semi-bold">Groups</h4>
                    </div>
                    <div class="col-md-6 col-xs-8 text-right p-t-10">
                        <a class="btn btn-info btn-xs btn-small" href="/admin/tournaments/{{ $tournament->id }}/participants/groups/export/csv">
                            <i class="fa fa-download"></i>
                            All Eligible Groups
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
                            <th class="col-md-3 text-center hidden-xs">Teams</th>
                            <th class="col-md-3 text-center hidden-xs">Players</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($groups) > 0)
                        @foreach ($groups as $group)
                            <tr>
                                <td>
                                    <a href="/admin/tournaments/{{ $tournament->id }}/registrations/groups/{{ $group->id }}" class="semi-bold">{{ $group->name }}</a><br/>
                                </td>
                                <td class="v-align-middle text-center hidden-xs">{{ number_format($group->team_count) }}</td>
                                <td class="v-align-middle text-center hidden-xs">{{ number_format($group->player_count) }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <div class="text-center">
                    {{ $groups->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection