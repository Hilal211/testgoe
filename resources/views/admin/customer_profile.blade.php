@extends('admin.layout.default')

@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>Shoppers</h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Shopper</h3>
					</div>
					<div class="box-body">
					@include('common.customer_profile',['user_details'=>$user_details,'shipping_details'=>$shipping_details,'type'=>'admin'])
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@stop

@section('custom_js')
@stop