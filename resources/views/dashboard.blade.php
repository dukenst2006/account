@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
	<div class="content sm-gutter">
		@include('partials.messages')
		<div class="row">
			<div class="col-md-6 m-b-10">
				<!-- BEGIN ROLE GUIDIANCE WIDGET -->
				<div class="tiles white add-margin">
					<div class="p-l-20 p-r-20 p-t-10">
						<div class="row b-grey b-b xs-p-b-20">
							<div class="col-md-7 col-sm-7">
								<h4 class="text-black semi-bold">For Parents</h4>
								<p class="text-gray">Add your children</p>
							</div>
							<div class="col-md-5 col-sm-5">
								<div class="m-t-20">
									<a class="btn btn-primary btn-cons" href="/player/create">Add my child(ren)</a>
								</div>
							</div>
						</div>
					</div>
					<div class="p-l-20 p-r-20 p-b-10">
						<div class="row xs-p-b-20">
							<div class="col-md-7 col-sm-7">
								<h4 class="text-black semi-bold">For Coaches</h4>
								<p class="text-gray">If you run a program, register it with NBB here</p>
							</div>
							<div class="col-md-5 col-sm-5">
								<div class="m-t-20">
									<button class="btn btn-primary btn-cons" type="submit">Add my program</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- END ROLE GUIDIANCE WIDGET -->
				@if(Auth::user()->hasRole(\BibleBowl\Role::GUARDIAN))
					@include('dashboard.guardian_players')
				@endif
			</div>
		</div>
	</div>
@endsection