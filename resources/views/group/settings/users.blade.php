@extends('layouts.master')

@section('title', 'Group Head Coaches')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12 horizontal-menu">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h3 class="semi-bold p-t-10 p-b-10">{{ $group->name }}</h3>
                    </div>
                    <div class="bar">
                        <div class="bar-inner">
                            @include('group.menu-partial', [
                                'selected' => 'users'
                            ])
                        </div>
                    </div>
                    <div class="grid-body no-border p-t-20"></div>
                    <div class="grid-body no-border p-t-20">
                        <div class="row m-b-10">
                            <div class="col-md-8">
                                Invite other users to help administer the group's roster, teams and tournament registrations.
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="/group/{{ $group->id }}/settings/users/invite" class="btn btn-primary">Invite User</a>
                            </div>
                        </div>
                        @include('partials.messages')
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Options</th>
                            </tr>
                            @foreach($users as $user)
                                <tr>
                                    <td class="v-align-middle">
                                        {{ $user->full_name }}
                                    </td>
                                    <td class="text-center v-align-middle">
                                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                    </td>
                                    <td class="text-center v-align-middle">
                                        @if($group->isOwner($user))
                                            Owner
                                        @else
                                            Head Coach
                                        @endif
                                    </td>
                                    <td class="text-center v-align-middle">
                                        @if($group->isOwner($user) === false)
                                            <a href="/player/{{ $child->id }}/edit">Remove</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection