@extends('layouts.master')

@section('title', $player->full_name)

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-title no-border">
                <div class="row">
                    <div class="col-md-8 col-xs-8">
                        <h3 class="semi-bold p-t-10 p-b-10">{{ $player->full_name }}</h3>
                    </div>
                    <div class="col-md-4 text-right p-r-20 p-t-15 text-black col-xs-4">
                        {!! Html::genderIcon($player->gender) !!} {!! \BibleBowl\Presentation\Describer::describeGender($player->gender) !!}<br/>
                        {{ $player->age() }} years old
                    </div>
                </div>
                <div class="b-grey b-b"></div>
            </div>
            <div class="grid-body no-border p-t-20">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fa fa-user"></i> <span class="semi-bold">Parent/Guardian</span></h5>
                        @include('partials.user-contact', [
                            'user' => $player->guardian,
                            'adminLink' => true
                        ])
                    </div>
                    <div class="col-md-6">
                        <h5 class="m-t-10"><i class="fa fa-map-marker"></i> Primary <span class="semi-bold">Address</span></h5>
                        <a href="http://maps.google.com/?q={{ $player->guardian->primaryAddress }}" title="View on a map" target="_blank">
                            @include('partials.address', [
                                'address' => $player->guardian->primaryAddress
                            ])
                        </a>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12">
                        <h5><i class="fa fa-users"></i> <span class="semi-bold">Seasons</span></h5>
                        <table class="table">
                            <tr>
                                <th>Season</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center hidden-xs">T-Shirt Size</th>
                                <th class="text-center">Group</th>
                            </tr>
                            @foreach ($seasons as $season)
                                <?php
                                $groupRegisteredWith = $player->groupRegisteredWith($season);
                                $isRegistered = $groupRegisteredWith !== null;
                                ?>
                                <tr>
                                    <td>{{ $season->name }}</td>
                                    @if($isRegistered)
                                        <td class="text-center">
                                            {{ \BibleBowl\Presentation\Describer::describeGradeShort($groupRegisteredWith->pivot->grade) }}
                                        </td>
                                        <td class="text-center hidden-xs">
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
                        @if(Auth::user()->isAn(\BibleBowl\Role::ADMIN) && count($seasons) == 0)
                            <div class="text-center m-b-10">
                                {!! Form::open(['url' => '/admin/players/'.$player->id, 'method' => 'delete']) !!}
                                <button class="btn btn-small btn-danger" data-toggle="tooltip" title="Only players who haven't participated in a season may be deleted." >Delete Player</button>
                                {!! Form::close() !!}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="text-center muted p-t-20" style="font-style:italic; font-size: 90%;">Last Updated: {{ $player->updated_at->timezone(Auth::user()->settings->timeszone())->format('F j, Y, g:i a') }} | Created: {{ $player->created_at->timezone(Auth::user()->settings->timeszone())->format('F j, Y, g:i a') }}</div>
            </div>
        </div>
    </div>
@endsection