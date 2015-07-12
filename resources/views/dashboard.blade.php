@extends('layouts.master')

@section('title', 'Dashboard')

@section('includeJs')
    <script src="{!! elixir('assets/js/dashboard.js') !!}" type="text/javascript"></script>
@endsection

@section('content')
    <div style="height: 76px"></div>

    @include('partials.messages')

    @if(Auth::user()->hasRole(\BibleBowl\Role::HEAD_COACH))
        <div class="col-md-12 m-b-10">
            @include('dashboard.players')
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