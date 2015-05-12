@extends('layouts.frontend_master')

@section('before-styles-end')
	<link href="/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
<body class="error-body no-top lazy" style="background-image: url('assets/img/work.jpg')">
<div class="container">
	<div class="row login-container column-seperation">
		<div class="col-md-5 col-md-offset-1">
			<h2>Bible Bowl Login</h2>
			<p>Use Facebook, Twitter, Google or your email to sign in.<br>
				<a href="/register">Sign up Now!</a> for a Bible Bowl account.</p>
			<br>

			<button class="btn btn-block btn-info col-md-8" type="button">
				<span class="pull-left"><i class="icon-facebook"></i></span>
				<span class="bold">Login with Facebook</span>
			</button>
			<button class="btn btn-block btn-success col-md-8" type="button">
				<span class="pull-left"><i class="icon-twitter"></i></span>
				<span class="bold">Login with Twitter</span>
			</button>
			<button class="btn btn-block btn-danger col-md-8" type="button">
				<span class="pull-left"><i class="icon-google-plus"></i></span>
				<span class="bold">Login with Google</span>
			</button>
		</div>
		<div class="col-md-5"> <br>
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

</html>
<!--
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Login</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> Remember Me
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">Login</button>

								<a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
-->
@endsection
