
<!DOCTYPE HTML>
<html>
@include('frontend.layout.head')
<body>
<div class="containern">

	<div class="global-wrapper clearfix" id="global-wrapper">
		@include('frontend.layout.header')
		@yield('content')
		<div class="gap gap-small"></div>
		@include('frontend.layout.footer')
	</div>
	<div class="containern">

	@include('frontend.layout.scripts')
</body>
</html>