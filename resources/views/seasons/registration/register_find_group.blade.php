@extends('layouts.master')

@section('title', 'Find your group')

@section('content')
    <div class="content">
        <h4>Your <span class="semi-bold">Registration</span> So Far</h4>
        <div class="grid simple">
            <div class="grid-body no-border" style="padding-bottom: 10px; padding-top: 20px">
                @include('seasons.registration.completed-steps.program', [
                    'program' => $program
                ])
            </div>
        </div>

        @if(!Input::has('q') && count($nearbyGroups) > 0)
            <h4>Select a <span class="semi-bold">Group Nearby</span></h4>
            @include('group.nearby', [
                'actionUrl' => '/register/'.$program->slug.'/group/[ID]',
                'actionButton' => 'Select this group'
            ])
        @endif

        <h4>Find <span class="semi-bold">Your Group</span></h4>
        <div class="grid simple">
            <div class="grid-body no-border">
                @include('seasons.registration.search_group', [
                    'actionUrl' => '/register/'.$program->slug.'/group/[ID]',
                    'actionButton' => 'Select this group'
                ])

                @if(Input::has('q'))
                <div class="form-actions text-center">
                    <p>You can always join your group later if you can't find them here</p>
                    <a href="/register/{{ $program->slug }}/group" class="btn btn-primary btn-cons">Join a Group Later</a>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection