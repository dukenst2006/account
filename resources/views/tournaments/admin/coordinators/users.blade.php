@extends('layouts.master')

@section('title', 'Tournament Coordinators')

@section('content')
    <div class="content">
        <div class="grid simple horizontal-menu">
            <div class="grid-title no-border">
                <h3 class="semi-bold p-t-10 m-l-15 p-b-15" style="margin-bottom: 0">{{ $tournament->name }}</h3>
            </div>
            <div class="bar">
                <div class="bar-inner">
                    @include('tournaments.admin.menu-partial', [
                        'selected' => 'Coordinators'
                    ])
                </div>
            </div>
            <div class="grid-body no-border p-t-20"></div>
            <div class="grid-body no-border p-t-20">
                <div class="row m-b-10">
                    <div class="col-md-8">
                        Invite other users to help administer the tournament details, registrations and more.
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="/admin/tournaments/{{ $tournament->id }}/coordinators/invite" class="btn btn-primary">Invite Coordinator</a>
                    </div>
                </div>
                @include('partials.messages')
                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center hidden-xs">Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Options</th>
                    </tr>
                    @foreach($tournament->coordinators as $coordinator)
                        <tr>
                            <td class="v-align-middle">
                                {{ $coordinator->full_name }}
                            </td>
                            <td class="text-center v-align-middle hidden-xs">
                                <a href="mailto:{{ $coordinator->email }}">{{ $coordinator->email }}</a>
                            </td>
                            <td class="text-center v-align-middle">
                                @if($tournament->isCreator($coordinator))
                                    Owner
                                @else
                                    Coordinator
                                @endif
                            </td>
                            <td class="text-center v-align-middle">
                                @if($tournament->isCreator($coordinator) === false)
                                    @if($coordinator->id == Auth::user()->id)
                                        <a href="/admin/tournaments/{{ $tournament->id }}/coordinators/{{ $coordinator->id }}/remove" class="btn btn-danger btn-small">Leave</a>
                                    @else
                                        <a href="/admin/tournaments/{{ $tournament->id }}/coordinators/{{ $coordinator->id }}/remove" class="btn btn-danger btn-small">Remove</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </thead>
                </table>

                @if(count($pendingInvitations) > 0)
                    <h4 class="semi-bold m-t-20">Pending Invitations</h4>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center hidden-xs">Invited</th>
                            <th class="text-center">Options</th>
                        </tr>
                        @foreach($pendingInvitations as $invitation)
                            <tr>
                                <td class="v-align-middle">
                                    @if ($invitation->user_id != null)
                                        {{ $invitation->user->full_name }}
                                    @endif
                                </td>
                                <td class="text-center v-align-middle">
                                    @if ($invitation->user_id != null)
                                        <a href="mailto:{{ $invitation->user->email }}">{{ $invitation->user->email }}</a>
                                    @else
                                        <a href="mailto:{{ $invitation->email }}">{{ $invitation->email }}</a>
                                    @endif
                                </td>
                                <td class="text-center v-align-middle hidden-xs">
                                    {{ $invitation->created_at->timezone(Auth::user()->settings->timeszone())->diffForHumans() }}
                                </td>
                                <td class="text-center v-align-middle">
                                    <a href="/admin/tournaments/{{ $tournament->id }}/coordinators/invite/{{ $invitation->id }}/retract" class="btn btn-danger btn-small">Retract</a>
                                </td>
                            </tr>
                        @endforeach
                        </thead>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection