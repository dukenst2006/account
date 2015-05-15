@extends('layouts.frontend_master')

@section('title', 'Login')

@section('content')
<body class="error-body no-top lazy" style="background-image: url('/img/work.jpg')">
<div class="container">
	<div class="row login-container column-seperation">
		<div class="col-md-5 col-md-offset-1">
			<h2>Bible Bowl Login</h2>
			<p>Use Facebook, Twitter, Google or your email to sign in.<br>
				<a href="/register">Sign up Now!</a> for a Bible Bowl account.</p>
			<br>

			<button class="btn btn-block btn-info col-md-8" type="button">
				<span class="pull-left"><i class="fa fa-facebook"></i></span>
				<span class="bold">Login with Facebook</span>
			</button>
			<button class="btn btn-block btn-success col-md-8" type="button">
				<span class="pull-left"><i class="fa fa-twitter"></i></span>
				<span class="bold">Login with Twitter</span>
			</button>
			<button class="btn btn-block btn-danger col-md-8" type="button">
				<span class="pull-left"><i class="fa fa-google-plus"></i></span>
				<span class="bold">Login with Google</span>
			</button>
		</div>
		<div class="col-md-5"> <br>
			@include('partials.messages')
			<form class="login-form" method="post">
				<input type="hidden" name="_token" value="{{ Session::token() }}" />
				<div class="row">
					<div class="form-group col-md-10">
						<label class="form-label">Email</label>
						<div class="controls">
							<div class="input-with-icon right">
								<i class=""></i>
								<input type="text" name="email" class="form-control" autofocus>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-10">
						<label class="form-label">Password</label>
						<span class="help"></span>
						<div class="controls">
							<div class="input-with-icon right">
								<i class=""></i>
								<input type="password" name="password" class="form-control" autocomplete="off">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="control-group col-md-10">
						<div class="checkbox checkbox check-success">
							<div class="row">
								<div class="col-md-6 pull-left">
									<input type="checkbox" name="remember" id="rememberMe">
									<label for="rememberMe">Remember me</label>
								</div>
								<div class="col-md-6 text-right">
									<a href="{{ url('/password/email') }}">Forgot your password?</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<button class="btn btn-primary btn-cons pull-right" type="submit">Login</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</body>
@endsection
