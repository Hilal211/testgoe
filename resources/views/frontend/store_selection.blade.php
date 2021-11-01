@extends('frontend.layout.home_default')
@section('content')
<div class="col-md-12"></div>
<div class="col-md-3">
	@include('frontend.layout.menu',$cats)
</div>
<div class="col-md-9">
	@include('frontend.layout.store_selection')
</div>
@include('common.zip_modal')
@stop

@section('page_custom_js')
<SCRIPT type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyA1GpIUeTcjRKCrf3asCqS-ZwrS_BU7vRY"></SCRIPT>
<script type="text/javascript">
	$(document).ready(function () {
		reloadStore();
		$('#nav-login-dialog').find('.checkout-error').parents('.alert').remove()
		//$('#offset').val('2')
	});
</script>
@stop