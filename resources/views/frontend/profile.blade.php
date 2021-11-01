@extends('frontend.layout.default')

@section('content')
<div class="container">
	<div class="gap gap-small"></div>
	<div class="row bg-orange-rounded">
		<div class="registration-bg form-group">
			<div class="col-md-8 col-md-push-2">
				<h1 class="widget-title text-center">{{trans('keywords.My Profile')}}</h1>
			</div>
		</div>
		<div>
			@include('common.customer_profile',['user_details'=>$user_details,'cust_details'=>$cust_details,'shipping_details'=>$shipping_details,'type'=>'front','states'=>$states,'cities'=>$cities])
		</div>
	</div>
</div>
@stop
@section('page_custom_js')
<script type="text/javascript">
	$(document).ready(function () {
        $('#subscribe').on('ifChanged', function () {
            $('#zip_holder').toggle();
        })
    });
</script>
@stop