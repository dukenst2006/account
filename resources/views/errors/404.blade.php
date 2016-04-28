@extends('layouts.frontend_master')

@section('title', 'Page Not Found')

@section('content')
	<div class="error-wrapper container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-offset-1 col-xs-10">
				<div class="error-container" >
					<div class="error-main">
						<div class="error-number"> 404 </div>
						<div class="error-description" > We seem to have lost you in the clouds. </div>
						<div class="error-description-mini"> The page you're looking for is not here </div>
					</div>
				</div>

			</div>
		</div>
	</div>
@endsection
