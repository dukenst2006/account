@extends('layouts.frontend_master')

@section('title', 'Forgot Password')

@section('content')
    @include('partials.logo-header')
    <div class="p-t-40">
        <div class="grid simple">
			<div class="col-md-8 col-md-offset-2 grid-body no-border">
				<br/>
				<div class="row">
					<div class="col-md-10 col-sm-8 col-xs-8">
						<div class="page-title">
							<h3>Password Reset Email</h3>
						</div>
					</div>
					<div class="col-md-2 col-sm-4 col-xs-4 text-right">
						<a href="/login">Back to login</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12"> <br>
						@include('partials.messages')
						{!! Form::open(['role' => 'form', 'url' => '/password/email']) !!}
							<div class="row">
								<div class="form-group col-md-12">
									<label class="form-label">E-Mail Address</label>
									<span class="help"></span>
									<div class="controls">
										<div class="input-with-icon right">
											<i class="icon-email"></i>
											<input type="email" name="email" class="form-control" value="{{ old('email') }}" autofocus>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 text-center p-t-20">
									<button class="btn btn-primary btn-cons" type="submit">Send Password Reset Link</button>
								</div>
							</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection