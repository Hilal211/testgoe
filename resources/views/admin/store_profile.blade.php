@extends('admin.layout.default')

@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>Profile</h1>
	</section>
	@include('common.store_profile',['type'=>"admin"])
</div>
@stop
@section('custom_js')

<script>
	$(document).ready(function(){
		var StripeId = $('input[name="stripe_account_id"]').val()
		if(StripeId != ''){
			$('#account_details_form').find('input').prop('readonly',true);
		}else{
			$('#account_details_form').find('input').prop('readonly',false);
		}
		var Id = $('input[name="id"]').val()

		if(Id!=''){
			//$('input[name="account_number"]').unmask().maskSSN('999-99999-9999', {maskedChar:'X', maskedCharsLength:7});;
		}
	});
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1GpIUeTcjRKCrf3asCqS-ZwrS_BU7vRY">
</script>
<script>
	var locations = [["{{$store->storename}} <br>{{$store->add1}}, {{$store->add2}}, {{$store->cityname}}, {{$store->statename}} {{$store->zip}}","{{$lat}}","{{$long}}"]];
	var centerPin = new google.maps.LatLng(locations[0][1], locations[0][2]);
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 4,
		center:centerPin,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var infowindow = new google.maps.InfoWindow();

	var marker, i;

	for (i = 0; i < locations.length; i++) {
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			map: map
		});

		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				infowindow.setContent(locations[i][0]);
				infowindow.open(map, marker);
			}
		})(marker, i));
	}
	google.maps.event.addDomListener(window, "resize", function() {
		var center = map.getCenter();
		google.maps.event.trigger(map, "resize");
		map.setCenter(center);
	});
</script>
@stop