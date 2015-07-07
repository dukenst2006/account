@extends('layouts.master')

@section('title', 'Dashboard')

@section('includeJs')
    <script src="{!! elixir('assets/js/dashboard.js') !!}" type="text/javascript"></script>
@endsection

@section('content')
	<div class="content">
		@include('partials.messages')

        @if(Auth::user()->hasRole(\BibleBowl\Role::GUARDIAN))
        <div class="col-md-12 m-b-10">
            @include('dashboard.guardian_children')
            <a href="/seasons/register">Register for {{ Session::season()->name }} season</a>
        </div>
        @endif

        @if(Auth::user()->hasRole(\BibleBowl\Role::HEAD_COACH))
            <div class="col-md-12 m-b-10">
                @include('dashboard.players')
            </div>
        @endif
        <div class="col-md-6 m-b-10">
            @include('dashboard.guidiance')
        </div>
	</div>
@endsection