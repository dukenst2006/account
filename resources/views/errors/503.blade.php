@extends('layouts.frontend_master')

@section('title', 'Temporarily Unavailable')

@section('content')
	<div class="error-wrapper container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-offset-1 col-xs-10">
				<div class="error-container" >
					<div class="error-main">
						<div class="error-number">&nbsp; </div>
						<div class="error-description" > Down for maintenance. </div>
						<div class="error-description-mini"> We'll be back soon! </div>
					</div>
				</div>

			</div>
		</div>
	</div>
@endsection
