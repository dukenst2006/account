@extends('layouts.master')

@section('title', $user->full_name)

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-title no-border">
                <div class="row">
                    <div class="col-md-1 col-sm-4">
                        <img src="{{ Gravatar::src(Auth::user()->email, 69) }}"  alt="" width="69" height="69" />
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <h3 class="semi-bold p-t-10 p-b-10 m-l-15">{{ $user->full_name }}</h3>
                    </div>
                    <div class="col-md-5 col-sm-12 text-right p-r-20 p-t-15">
                        @if(!is_null($user->phone))
                            <a href='tel:+1{{ $user->phone }}'>{{ HTML::formatPhone($user->phone) }}</a><br/>
                        @endif
                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        @if($user->status == \BibleBowl\User::STATUS_UNCONFIRMED)
                            <span class="text-muted">(unconfirmed)</span>
                        @endif
                        <br/>
                        @if(\Bouncer::allows(BibleBowl\Ability::SWITCH_ACCOUNTS))
                            <a href="/admin/switchUser/{{ $user->id }}" class="btn btn-white btn-xs btn-mini"><i class="fa fa-exchange"></i> Login as this user</a>
                        @endif
                    </div>
                </div>
                <div class="b-grey b-b m-t-10"></div>
            </div>
            <div class="grid-body no-border p-t-20">
                <div class="row">
                    <div class="col-md-4 col-sm-6">
                        <h5><i class="fa fa-map-marker"></i> Primary <span class="semi-bold">Address</span></h5>
                        <a href="http://maps.google.com/?q={{ $user->primaryAddress }}" title="View on a map" target="_blank">
                            @include('partials.address', [
                                'address' => $user->primaryAddress
                            ])
                        </a>
                    </div>
                    <div class="col-md-3">
                        <h5><i class="fa fa-lock"></i> <span class="semi-bold">Roles</span></h5>
                        <ul>
                            @foreach ($user->roles as $role)
                                <li>{{ ucwords(str_replace('-', ' ', $role->name)) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-5 col-sm-6">
                        @if($user->is(\BibleBowl\Role::HEAD_COACH))
                            <h5><i class="fa fa-house"></i> <span class="semi-bold">Groups</span></h5>
                            <ul>
                                @foreach ($user->groups()->with('program')->get() as $group)
                                    <li>
                                        <a href="/admin/groups/{{ $group->id }}">{{ $group->name }}</a>
                                        <div class="text-muted">{{ $group->program->name }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12">
                        @if($user->is(\BibleBowl\Role::GUARDIAN))
                            <h5><i class="fa fa-users"></i> <span class="semi-bold">Students</span></h5>
                        <table class="table no-more-tables">
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Gender</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center">T-Shirt Size</th>
                                <th class="text-center">{{ Session::season()->name }} Registration</th>
                            </tr>
                            @foreach ($user->players as $player)
                                <?php
                                $groupRegisteredWith = $player->groupRegisteredWith(Session::season());
                                $isRegistered = $groupRegisteredWith !== null;
                                ?>
                                <tr>
                                    <td><a href="/admin/players/{{ $player->id }}">{{ $player->full_name }}</a></td>
                                    <td class="text-center">{!! HTML::genderIcon($player->gender) !!}</td>
                                    @if($isRegistered)
                                        <td class="text-center">
                                            {{ \BibleBowl\Presentation\Describer::describeGradeShort($groupRegisteredWith->pivot->grade) }}
                                        </td>
                                        <td class="text-center">
                                            {{ $groupRegisteredWith->pivot->shirt_size }}
                                        </td>
                                    @else
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                    @endif
                                    <td class="text-center">
                                        @if($isRegistered)
                                            <a href="/admin/groups/{{ $groupRegisteredWith->id }}">{{ $groupRegisteredWith->name }}</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        @endif
                    </div>
                </div>
                <div class="text-center muted p-t-20" style="font-style:italic; font-size: 90%;">Last Login: @if(is_null($user->last_login))
                            never
                        @else
                            {{ $user->last_login->timezone(Auth::user()->settings->timeszone())->format('F j, Y, g:i a') }}
                        @endif |
                        Last Updated: {{ $user->updated_at->timezone(Auth::user()->settings->timeszone())->format('F j, Y, g:i a') }} |
                        Created: {{ $user->created_at->timezone(Auth::user()->settings->timeszone())->format('F j, Y, g:i a') }}
                </div>
            </div>
        </div>
    </div>
@endsection