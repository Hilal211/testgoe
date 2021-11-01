<!DOCTYPE HTML>
<html>
@include('frontend.layout.head')
<body>
	<div class="global-wrapper clearfix" id="global-wrapper">
		@include('frontend.layout.header')
		<div class="container">
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

