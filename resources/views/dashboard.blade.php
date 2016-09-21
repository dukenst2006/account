@extends('layouts.master')

@section('title', 'Dashboard')

@includeJs(elixir('assets/js/dashboard.js'))

@section('content')
    <div style="height: 76px"></div>

    <div class="m-l-15 m-r-15">
        @include('partials.messages')
    </div>

    @if(isset($rosterOverview))
        <div class="col-md-12 m-b-10">
            @include('dashboard.roster-overview', $rosterOverview)
        </div>
    @endif

    @if(Auth::user()->isA(\BibleBowl\Role::GUARDIAN))
        <div class="col-md-12 m-b-10">
            @include('dashboard.guardian-children')
        </div>
    @endif

    @if(Bouncer::allows(\BibleBowl\Ability::VIEW_REPORTS))
        <div class="col-md-6">
            @include('dashboard.season-overview')
        </div>
    @endif

    @if(Auth::user()->isNotA(\BibleBowl\Role::HEAD_COACH) || Auth::user()->isNotA(\BibleBowl\Role::GUARDIAN))
        <div class="col-md-6">
            @include('dashboard.guidiance')
        </div>
    @endif

    @if(Auth::user()->isA(\BibleBowl\Role::HEAD_COACH))
    <div class="col-md-6">
        @include('dashboard.registration-payment')
    </div>
    @endif

    @if(Auth::user()->isA(\BibleBowl\Role::HEAD_COACH))
        @include('dashboard.tournaments')
    @endif
@endsection