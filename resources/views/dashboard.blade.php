@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
	<div class="content">
		@include('partials.messages')
		<div class="row">
			<div class="col-md-6 m-b-10">
				@include('dashboard.guidiance')
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 m-b-10">
				@if(Auth::user()->hasRole(\BibleBowl\Role::GUARDIAN))
					@include('dashboard.guardian_players')
				@endif
			</div>
		</div>
	</div>
@endsection