
@extends('frontend.layout.home_default')
@section('content')
@if(Session::get('success')!='' || Session::get('newsletter_success')!='' || Session::get('order_success')!='')
<div class="col-md-12">
	@include('common.message')
</div>
@endif
<div class="col-md-3">
	@include('frontend.layout.menu',$cats)
</div>
<div class="col-md-9">
	@include('frontend.layout.banner')
	@include('frontend.layout.cart')
</div> 
@include('common.zip_modal')
<div class="gap gap-small"></div>
@stop

@section('page_custom_js')
<SCRIPT type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyA1GpIUeTcjRKCrf3asCqS-ZwrS_BU7vRY"></SCRIPT>
@stop