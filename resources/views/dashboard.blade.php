@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
	<div class="content">
		@include('partials.messages')

        @if(Auth::user()->hasRole(\BibleBowl\Role::GUARDIAN))
        <div class="row">
            <div class="col-md-12 m-b-10">
                @include('dashboard.guardian_players')
                <a href="/seasons/register">Register for {{ Session::season()->name }} season</a>
            </div>
        </div>
        @endif

		<div class="row">
			<div class="col-md-6 m-b-10">
				@include('dashboard.guidiance')
			</div>
		</div>
	</div>
@endsection