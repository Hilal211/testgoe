<!DOCTYPE html>
<html>
  @include('admin.layout.head')
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		@include('admin.layout.header')
		@include('admin.layout.menu')
		@yield('content')
		@yield('left_sidebar')
		@include('admin.layout.footer')
	</div>
	@include('admin.layout.scripts')
</body>
</html>