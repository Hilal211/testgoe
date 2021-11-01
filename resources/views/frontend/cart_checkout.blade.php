
@extends('frontend.layout.home_default')
@section('content')
<div class="col-md-12"></div>
<div class="col-md-3">
    @include('frontend.layout.menu',$cats)
</div>
<div class="col-md-9">
	@include('errors.error')
    @include('frontend.layout.cart_checkout')
</div>
@stop

@section('page_custom_js')
<SCRIPT type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyA1GpIUeTcjRKCrf3asCqS-ZwrS_BU7vRY"></SCRIPT>
<script type="text/javascript">
	var TodaySlot = '{!! json_encode($filtered_slots) !!}';
	var OtherSlot = '{!! json_encode($slots) !!}';
	var CurrentHour = '{{ $current_slot['current_hour'] }}';
	var NextHour = '{{ $current_slot['next_hour'] }}';
    // $(document).ready(function () {
    //     reloadStoreProducts({{$sid}});
    // });
</script>
@stop