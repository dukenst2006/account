@extends('layouts.master')

@section('title', $guardian->full_name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-body no-border">
                        <div class="row m-t-20">
                            <div class="col-md-3">
                                <h5><i class="fa fa-phone"></i> <span class="semi-bold">Guardian</span></h5>
                                @include('partials.user-contact', [
                                    'user' => $guardian
                                ])
                                <h5 class="m-t-20"><i class="fa fa-map-marker"></i> <span class="semi-bold">Primary Address</span></h5>
                                <a href="http://maps.google.com/?q={{ urlencode($guardian->primaryAddress) }}" target="_blank">
                                @include('partials.address', [
                                    'address' => $guardian->primaryAddress
                                ])
                                </a>
                            </div>
                            <div class="col-md-9">
                                <h5><i class="fa fa-users"></i> <span class="semi-bold">Players</span></h5>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Player</th>
                                        <th class="text-center">Age</th>
                                        <th class="text-center">Seasons Played</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($players as $player)
                                        <tr>
                                            <td class="v-align-middle">{{ $player->full_name }}</td>
                                            <td class="v-align-middle text-center">{{ $player->age() }}</td>
                                            <td class="v-align-middle text-center">{{ $player->seasons()->count() }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection