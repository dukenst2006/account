@extends('layouts.master')

@section('title', $player->full_name)

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-title no-border">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="semi-bold p-t-10 p-b-10">{{ $player->full_name }}</h3>
                        <div class="b-grey b-b m-t-10"></div>
                    </div>
                    <div class="col-md-4 text-right p-r-20 p-t-15 text-black">
                        {!! HTML::genderIcon($player->gender) !!} {!! \BibleBowl\Presentation\Describer::describeGender($player->gender) !!}<br/>
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
                        <table class="table no-more-tables">
                            <tr>
                                <th>Season</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center">T-Shirt Size</th>
                                <th class="text-center">Participated</th>
                                <th class="text-center">Group</th>
                            </tr>
                            @foreach ($player->seasons()->orderBy('id', 'desc')->get() as $season)
                                <?php
                                $isRegisteredWithNBB = $player->isRegisteredWithNBB($season);
                                if ($isRegisteredWithNBB) {
                                    $registration = $player->registration($season);
                                }
                                ?>
                                <tr>
                                    <td>{{ $season->name }}</td>
                                    @if($isRegisteredWithNBB)
                                        <td class="text-center">
                                            {{ \BibleBowl\Presentation\Describer::describeGradeShort($registration->grade) }}
                                        </td>
                                        <td class="text-center">
                                            {{ $registration->shirt_size }}
                                        </td>
                                    @else
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                    @endif
                                    <td class="text-center">
                                        @if($isRegisteredWithNBB)
                                            <div class="fa fa-check"></div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(is_null($group = $player->groupRegisteredWith($season)) === false)
                                            <a href="/admin/groups/{{ $group->id }}">{{ $group->name }}</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="text-center muted p-t-20" style="font-style:italic; font-size: 90%;">Last Updated: {{ $player->updated_at->format('F j, Y, g:i a') }} | Created: {{ $player->created_at->format('F j, Y, g:i a') }}</div>
            </div>
        </div>
    </div>
@endsection