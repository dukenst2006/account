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
            @include('dashboard.roster_overview', $rosterOverview)
        </div>
    @endif

    @if(Auth::user()->hasRole(\BibleBowl\Role::GUARDIAN))
        <div class="col-md-12 m-b-10">
            @include('dashboard.guardian_children')
        </div>
    @endif

    @if(!Auth::user()->hasRole(\BibleBowl\Role::HEAD_COACH) || !Auth::user()->hasRole(\BibleBowl\Role::GUARDIAN))
    <div class="col-md-6">
        @include('dashboard.guidiance')
    </div>
    @endif
@endsection