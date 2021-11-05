@extends('frontend.layout.default')

@section('content')
<div class="" style="width:100% !important;">
	<div class="gap gap-small"></div>
	<div class="row bg-orange-rounded">
		<div class="registration-bg form-group">
			<div class="col-md-8 col-md-push-2">
				<h1 class="widget-title text-center">{{trans('keywords.How it works')}}</h1>
			</div>
		</div>
		<div>
			<div class="col-md-8 col-md-push-2">
				<div class="gap gap-small gap-border"></div>
				<div class="owl-carousel owl-loaded owl-nav-dots-inner owl-carousel-curved" data-options='{"items":1,"loop":true,"autoplay":true,"autoplayTimeout":9500}'>
					<div class="owl-item">
						<div class="slider-item" style="background-image:url({{url(config('theme.ASSETS').config('theme.FRONT_IMG').'banner_GIF/banner_1_'.App::getLocale().'.gif')}}); height:600px;">
						</div>
					</div>
					<div class="owl-item">
						<div class="slider-item" style="background-image:url({{url(config('theme.ASSETS').config('theme.FRONT_IMG').'banner_GIF/banner_2_'.App::getLocale().'.gif')}}); height:600px;">
						</div>
					</div>
					<div class="owl-item">
						<div class="slider-item" style="background-image:url({{url(config('theme.ASSETS').config('theme.FRONT_IMG').'banner_GIF/banner_3_'.App::getLocale().'.gif')}}); height:600px;">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop