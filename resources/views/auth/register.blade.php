@extends('layouts.frontend_master')

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
						<form method="post">
							<input type="hidden" name="_token" value="{{ Session::token() }}" />
							<div class="row">
								<div class="form-group col-md-12">
									<label class="form-label">Name</label>
									<span class="help"></span>
									<div class="row">
										<div class="col-md-6">
											<input type="text" name="first_name" class="form-control" autofocus placeholder="First">
										</div>
										<div class="col-md-6">
											<input type="text" name="last_name" class="form-control" placeholder="Last">
										</div>
									</div>
								</div>
							</div>
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
							<div class="row">
								<div class="col-md-12 text-center">
									<button class="btn btn-primary btn-cons" type="submit">Register</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection