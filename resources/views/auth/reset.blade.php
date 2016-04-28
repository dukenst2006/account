@extends('layouts.frontend_master')

@section('title', 'Reset Password')

@section('content')
    @include('partials.logo-header')
    <div class="p-t-40">
        <div class="grid simple">
			<div class="col-md-8 col-md-offset-2 grid-body no-border">
				<br/>
				<div class="row">
					<div class="col-md-12 page-title">
						<h3>Reset Password</h3>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12"> <br>
						@include('partials.messages')
						<form role="form" method="POST" action="{{ url('/password/reset') }}">
							<input type="hidden" name="_token" value="{{ Session::token() }}" />
							<input type="hidden" name="token" value="{{ $token }}">
							<div class="row">
								<div class="form-group col-md-12">
									<label class="form-label">E-Mail Address</label>
									<span class="help"></span>
									<div class="controls">
										<input type="text" name="email" class="form-control" autofocus value="{{ old('email') }}">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label class="form-label">Password</label>
									<span class="help"></span>
									<div class="controls">
										<div class="input-with-icon right">
											<i class="icon-email"></i>
											<input type="password" name="password" class="form-control">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label class="form-label">Confirm Password</label>
									<span class="help"></span>
									<div class="controls">
										<div class="input-with-icon right">
											<i class="icon-email"></i>
											<input type="password" name="password_confirmation" class="form-control">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary btn-cons pull-right" type="submit">Reset Password</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
