@extends('layouts.master')

@section('title', $group->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-1 col-md-10 horizontal-menu">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h3 class="semi-bold p-t-10 p-b-10">{{ $group->name }}</h3>
                        <div class="b-grey b-b"></div>
                    </div>
                    <div class="grid-body no-border p-t-20">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fa fa-user"></i> <span class="semi-bold">Owner</span></h5>
                                @include('partials.user-contact', [
                                    'user' => $group->owner,
                                    'adminLink' => true
                                ])
                            </div>
                            <div class="col-md-3">
                                <h5><i class="fa fa-map-marker"></i> Meeting <span class="semi-bold">Location</span></h5>
                                <a href="http://maps.google.com/?q={{ $group->meetingAddress }}" title="View on a map" target="_blank">
                                    @include('partials.address', [
                                        'address' => $group->meetingAddress
                                    ])
                                </a>
                            </div>
                            <div class="col-md-3">
                                <h5><i class="fa fa-map-marker"></i> Mailing <span class="semi-bold">Address</span></h5>
                                <a href="http://maps.google.com/?q={{ $group->address }}" title="View on a map" target="_blank">
                                    @include('partials.address', [
                                        'address' => $group->address
                                    ])
                                </a>
                            </div>
                        </div>
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