@extends('layouts.frontend_master')

@section('title', 'Account Registration')

@section('content')
    @include('partials.logo-header')
    <div class="p-t-40">
		<div class="grid simple">
			<div class="col-md-8 col-md-offset-2 grid-body no-border">
				<br/>
				<div class="row">
					<div class="col-md-8 col-sm-8 pull-left">
						<div class="page-title">
							<h3>Bible Bowl Account Registration</h3>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 text-right">
						<a href="/login">Back to login</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-sm-12 p-b-10">
						<p>Once you have an account you can register your students to play with your local group, start your own group and more!  Login with your <span class="bold">favorite social network</span> or start fresh.</p>
					</div>
				</div>
				<div class="row column-seperation">
					<div class="col-md-4 col-sm-4 p-t-40 p-b-40">
						<a href='/login/{{ \BibleBowl\Users\Auth\ThirdPartyAuthenticator::PROVIDER_FACEBOOK }}' class="btn btn-block btn-info col-md-8">
							<span class="pull-left"><i class="fa fa-facebook"></i></span>
							<span class="bold">Login with Facebook</span>
						</a>
						<a href='/login/{{ \BibleBowl\Users\Auth\ThirdPartyAuthenticator::PROVIDER_GOOGLE }}' class="btn btn-block btn-danger col-md-8">
							<span class="pull-left"><i class="fa fa-google-plus"></i></span>
							<span class="bold">Login with Google</span>
						</a>
						<a href='/login/{{ \BibleBowl\Users\Auth\ThirdPartyAuthenticator::PROVIDER_TWITTER }}' class="btn btn-block btn-success col-md-8">
							<span class="pull-left"><i class="fa fa-twitter"></i></span>
							<span class="bold">Login with Twitter</span>
						</a>
					</div>
					<div class="col-md-8 col-sm-8">
                        <br>
						@include('partials.messages')
						{!! Form::open(['method' => 'post']) !!}
							<div class="row">
								<div class="form-group col-md-12">
									<label class="form-label">E-Mail Address</label>
									<span class="help"></span>
									<div class="controls">
										<div class="input-with-icon right">
											<i class="icon-email"></i>
											{!! Form::email('email', old('email'), ['class' => 'form-control', 'autofocus', 'maxlength' => 64]) !!}
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label class="form-label">Password</label>
									<span class="help"></span>
									<div class="row">
										<div class="col-md-6 col-sm-6">
											<input type="password" name="password" class="form-control" placeholder="Password">
										</div>
										<div class="col-md-6 col-sm-6">
											<input type="password" name="password_confirmation" class="form-control" placeholder="Password confirmation">
										</div>
									</div>
								</div>
							</div>
							@if(!App::environment('local', 'testing'))
							<div class="row">
								<div class="col-md-3 col-sm-3"></div>
								<div class="col-md-5 col-sm-5 text-center">
									{!! app('captcha')->display() !!}
									<br/>
								</div>
								<div class="col-md-3 col-sm-3"></div>
							</div>
							@endif
							<div class="row">
								<div class="col-md-12 col-sm-12 text-center">
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