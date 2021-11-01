@extends('frontend.layout.default')

@section('content')
<div class="container">
	<div class="gap gap-small"></div>
	<div class="row bg-white-rounded">
		<div class="col-md-8 col-md-push-2">
			<h1 class="widget-title text-center">{{trans('keywords.Settings')}}</h1>
			<div class="gap gap-small gap-border"></div>
			<p class="text-center">{{trans('keywords.Coming soon...')}}</p>
		</div>
	</div>
</div>
@stop
@section('page_custom_js')
@stop