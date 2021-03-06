@extends('layouts.frontend_master')

@section('title', 'Login')

@css
        #footer a,
        #footer {
            color: #fff;
        }
        .copyright {
            color: #fff;
        }
        body {
            background: no-repeat url('/img/login-background.png') 50% 50% !important;
        }
@endcss

@section('content')
	<body class="error-body no-top lazy" style="background-color: #396fa4 !important;">
	<div class="container" style="margin-top: 10%;">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 tiles white no-padding">
				<div class="p-t-30 p-l-40 p-r-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-b-10">
					<div class="text-center p-b-20 xs-p-b-10">
						<img src="/img/logo-blue.png" style="width: 250px"/>
					</div>
					<p>Manage your player information, team rosters and more!  To get started, <strong>login with your favorite social network</strong> or <strong><a href="/register">register a new account</a></strong>.<br></p>
					@include('partials.messages')
				</div>
				<div class="tiles grey text-black">
					<div class="row p-t-20 p-b-20">
						<div class="col-sm-5 col-sm-offset-0 col-md-5 col-md-offset-0 col-xs-10 col-xs-offset-1 p-l-30">
							<a href='/login/{{ \App\Users\Auth\ThirdPartyAuthenticator::PROVIDER_FACEBOOK }}' class="btn btn-block btn-info col-md-8">
								<span class="pull-left"><i class="fa fa-facebook"></i></span>
								<span class="bold">Login with Facebook</span>
							</a>
							<a href='/login/{{ \App\Users\Auth\ThirdPartyAuthenticator::PROVIDER_GOOGLE }}' class="btn btn-block btn-danger col-md-8">
								<span class="pull-left"><i class="fa fa-google-plus"></i></span>
								<span class="bold">Login with Google</span>
							</a>
							<a href='/login/{{ \App\Users\Auth\ThirdPartyAuthenticator::PROVIDER_TWITTER }}' class="btn btn-block btn-success col-md-8">
								<span class="pull-left"><i class="fa fa-twitter"></i></span>
								<span class="bold">Login with Twitter</span>
							</a>
						</div>
						<div class="col-sm-6 col-sm-offset-0 col-md-6 col-md-offset-0 col-xs-10 col-xs-offset-1 p-l-10 p-r-10">
                            <div class="hidden-xl hidden-lg hidden-md hidden-sm text-center p-b-15">&nbsp;</div>
                            {!! Form::open(['class' => 'login-form', 'role' => 'form']) !!}
								<div class="row">
									<div class="form-group col-md-11 col-sm-11 col-xs-10 col-xs-offset-1">
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
									<div class="form-group col-md-11 col-sm-11 col-xs-10 col-xs-offset-1">
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
									<div class="control-group col-md-11 col-sm-11 col-xs-10 col-xs-offset-1">
										<div class="checkbox checkbox check-success">
											<div class="row">
												<div class="col-md-6 pull-left">
													<input type="checkbox" name="remember" id="rememberMe">
													<label for="rememberMe">Remember me</label>
												</div>
												<div class="col-md-6 text-right">
													<a href="{{ url('/password/reset') }}">Forgot your password?</a>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 text-center p-t-15">
										<button class="btn btn-primary btn-cons" type="submit">Login</button>
									</div>
								</div>
                            {!! Form::close() !!}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</body>
@endsection
