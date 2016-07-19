@extends('layouts.master')

@section('title', $group->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12 horizontal-menu">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <div class="row b-grey b-b">
                            <div class="col-md-9">
                                <h3 class="semi-bold p-t-10 p-b-10">{{ $group->name }}</h3>
                            </div>
                            <div class="col-md-3 text-right p-r-20 p-t-15 text-black">
                                {{ $group->program->name }}
                            </div>
                        </div>
                    </div>
                    <div class="grid-body no-border p-t-20">
                        <div class="row">
                            <div class="col-md-4">
                                <h5><i class="fa fa-user"></i> <span class="semi-bold">Owner</span></h5>
                                @include('partials.user-contact', [
                                    'user' => $group->owner,
                                    'adminLink' => true
                                ])
                            </div>
                            <div class="col-md-4">
                                <h5><i class="fa fa-map-marker"></i> Meeting <span class="semi-bold">Location</span></h5>
                                <a href="http://maps.google.com/?q={{ $group->meetingAddress }}" title="View on a map" target="_blank">
                                    @include('partials.address', [
                                        'address' => $group->meetingAddress
                                    ])
                                </a>
                            </div>
                            <div class="col-md-4">
                                <h5><i class="fa fa-map-marker"></i> Mailing <span class="semi-bold">Address</span></h5>
                                <a href="http://maps.google.com/?q={{ $group->address }}" title="View on a map" target="_blank">
                                    @include('partials.address', [
                                        'address' => $group->address
                                    ])
                                </a>
                            </div>
                        </div>
                        @if($group->isActive())
                        <div class="row m-t-20">
                            <div class="col-md-12">
                                <h5><i class="fa fa-users"></i> <span class="semi-bold">{{ $season->name }} Players</span></h5>
                                    <ul class="nav nav-tabs" id="roster-tabs">
                                        <li class="active"><a href="#tabActive">Active ({{ $activePlayers->count() }})</a></li>
                                        <li><a href="#tabInactive">Inactive ({{ $inactivePlayers->count() }})</a></li>
                                        <li><a href="#tabPendingRegistration">Unpaid Registration Fees ({{ $pendingPaymentPlayers->count() }})</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tabActive">
                                            @include('admin.groups.partials.players', [
                                                'players' => $activePlayers,
                                                'extraColumns' => [
                                                    'T-Shirt Size' => 'pivot->shirt_size'
                                                ]
                                            ])
                                        </div>
                                        <div class="tab-pane" id="tabInactive">
                                            @include('admin.groups.partials.players', [
                                                'players' => $inactivePlayers,
                                                'extraColumns' => [
                                                    'Date*' => 'pivot->inactive|date'
                                                ]
                                            ])
                                            <p class="muted">* The date the player was flagged inactive</p>
                                        </div>
                                        <div class="tab-pane" id="tabPendingRegistration">
                                            @include('admin.groups.partials.players', [
                                                'players' => $pendingPaymentPlayers,
                                                'extraColumns' => [
                                                    'Registered' => 'created_at|date'
                                                ]
                                            ])
                                        </div>
                                    </div>
                                </div>
                        </div>

                            @js
                            $(document).ready(function() {
                                $('#roster-tabs a').click(function (e) {
                                    e.preventDefault();
                                    $(this).tab('show');
                                });
                            });
                            @endjs
                        @endif
                        <div class="text-center muted p-t-20" style="font-style:italic; font-size: 90%;">Last Updated: {{ $group->updated_at->timezone(Auth::user()->settings->timeszone())->format('F j, Y, g:i a') }} | Created: {{ $group->created_at->timezone(Auth::user()->settings->timeszone())->format('F j, Y, g:i a') }}</div>
                        @if($group->isInactive())
                            <span class="center text-error">Inactive as of {{ $group->inactive->timezone(Auth::user()->settings->timeszone())->format('m/d/Y at g:i a') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection