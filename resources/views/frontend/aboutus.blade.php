@extends('frontend.layout.default')

@section('content')
<div class="container">
	<div class="gap gap-small"></div>
	<div class="row bg-orange-rounded">
		<div class="registration-bg form-group">
			<div class="col-md-8 col-md-push-2">
				<h1 class="widget-title text-center">{{trans('keywords.About Us')}}</h1>
			</div>
		</div>
		<div>
			<div class="col-md-8 col-md-push-2">
				<p class="text-center">
					{{trans('keywords.We are looking to make the world a better place, allowing you to purchase the best products in your vicinity at the best price possible, and have them delivered to your doorsteps.')}}
				</p>
				<p class="text-center">
					{{trans('keywords.We want to help you have a positive impact on your community, environment and pocket.')}}
				</p>
				<p class="text-center">
					{{trans('keywords.Join us and get empowered.')}}
				</p>
				<div class="gap gap-small gap-border"></div>
			</div>
		</div>
	</div>
</div>
@stop