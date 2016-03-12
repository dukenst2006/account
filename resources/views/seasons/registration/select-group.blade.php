@extends('layouts.master')

@section('title', 'Find your group')

@section('content')
    <div class="content">
        @if(!Input::has('q') && count($nearbyGroups) > 0)
            <h4>Select a <span class="semi-bold">{{ $program->abbreviation }} Group Nearby</span></h4>
            @include('group.nearby', [
                'actionUrl' => '/register/'.$program->slug.'/group/[ID]',
                'actionButton' => 'Select this group'
            ])
        @endif

        <h4>Find <span class="semi-bold">Your {{ $program->abbreviation }} Group</span></h4>
        <div class="grid simple">
            <div class="grid-body no-border">
                @include('seasons.registration.partials.group-search', [
                    'actionUrl' => '/register/'.$program->slug.'/group/[ID]',
                    'actionButton' => 'Select this group'
                ])

                @if(Input::has('q'))
                <div class="form-actions text-center" style="margin-right: -11px !important; margin-left: -11px !important;">
                    <p>If you can't find the {{ strtolower($program->abbreviation) }} group you're looking for, the <strong>Head Coach</strong> may not have created it or it may be inactive.</p>
                    <a href="/register/{{ $program->slug }}/later" class="btn btn-primary btn-cons">Register Later</a>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection