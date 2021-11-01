@extends('frontend.layout.default')

@section('content')
<div class="container">
	<div class="gap gap-small"></div>
	<div class="row bg-orange-rounded">
		<div class="registration-bg form-group">
			<div class="col-md-8 col-md-push-2">
				<h1 class="widget-title text-center">{{trans('keywords.Privacy Policy')}}</h1>
				<p class="description text-center">{{trans('keywords.Read Our Privacy Policy')}}</p>
			</div>
		</div>
		<div>
			<div>
				<div class="gap gap-small gap-border"></div>
				<p class="text-center">{{trans('keywords.Coming soon...')}}</p>
			</div>
		</div>
	</div>
</div>
	@stop