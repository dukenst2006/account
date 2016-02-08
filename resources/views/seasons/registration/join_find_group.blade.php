@extends('layouts.master')

@section('title', 'Find your group')

@section('content')
    <div class="content">

        @if(!Input::has('q') && count($nearbyGroups) > 0)
            <h4>Select a <span class="semi-bold">{{ $program->abbreviation }} Group Nearby</span></h4>
            @include('group.nearby', [
                'actionUrl' => '/join/'.$program->slug.'/group/[ID]',
                'actionButton' => 'Join this group'
            ])
        @endif

            <h4>Find <span class="semi-bold">Your {{ $program->abbreviation }} Group</span></h4>
        <div class="grid simple">
            <div class="grid-body no-border">
                @include('seasons.registration.search_group', [
                    'actionUrl' => '/join/'.$program->slug.'/group/[ID]',
                    'actionButton' => 'Join this group'
                ])
            </div>
        </div>
    </div>
@endsection