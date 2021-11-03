<!DOCTYPE HTML>
<html>
@include('frontend.layout.head')
<body>
	<div class="global-wrapper clearfix" id="global-wrapper">
		@include('frontend.layout.header')
		<div class="container">
		<div>
		@if($ann!=null)
		@if(trans('keywords.image_announcement')=="english_image")
		{{ Html::image(Functions::UploadsPath(config('theme.COUPON_UPLOAD')).Functions::GetImageName($ann->english_image,''),"",['width'=>'100%','height'=>'100%']) }}
		@else
		{{ Html::image(Functions::UploadsPath(config('theme.COUPON_UPLOAD')).Functions::GetImageName($ann->francais_image,''),"",['width'=>'100%','height'=>'100%']) }}
		@endif
		@endif
	</div>
			@include('frontend.layout.search')
			<div class="row" data-gutter="15">
				@yield('content')
			</div>
			<div class="gap gap-small"></div>
		</div>
		@include('frontend.layout.footer')
	</div>
	@include('frontend.layout.scripts')
</body>
</html>

