@extends('layouts.frontend_master')

@section('title', 'Account Registration')

@section('content')
	<div class="login-container row">
		<div class="grid simple">
			<div class="col-md-8 col-md-offset-2 grid-body no-border">
				<br/>
				<div class="row">
					<div class="col-md-8 pull-left">
						<div class="page-title">
							<h3>Bible Bowl Account Registration</h3>
						</div>
					</div>
					<div class="col-md-4 text-right">
						<a href="/login">Back to login</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12"> <br>
						@include('partials.messages')
						{!! Form::open(['method' => 'post']) !!}
							<div class="row">
								<div class="form-group col-md-12">
									<label class="form-label">E-Mail Address</label>
									<span class="help"></span>
									<div class="controls">
										<div class="input-with-icon right">
											<i class="icon-email"></i>
											<input type="text" name="email" class="form-control" autofocus>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label class="form-label">Password</label>
									<span class="help"></span>
									<div class="row">
										<div class="col-md-6">
											<input type="password" name="password" class="form-control" placeholder="Password">
										</div>
										<div class="col-md-6">
											<input type="password" name="password_confirmation" class="form-control" placeholder="Password confirmation">
										</div>
									</div>
								</div>
							</div>
							@if(!App::environment('local'))
							<div class="row">
								<div class="col-md-3"></div>
								<div class="col-md-5 text-center">
									{!! app('captcha')->display(); !!}
									<br/>
								</div>
								<div class="col-md-3"></div>
							</div>
							@endif
							<div class="row">
								<div class="col-md-12 text-center">
									<button class="btn btn-primary btn-cons" type="submit">Register</button>
								</div>
							</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection