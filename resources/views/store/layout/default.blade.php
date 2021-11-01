<!DOCTYPE html>
<html>
  @include('store.layout.head')
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		@include('store.layout.header')
		@include('store.layout.menu')
		@yield('content')
		@yield('left_sidebar')
		@include('admin.layout.footer')
	</div>
	@include('store.layout.scripts')
</body>
</html>