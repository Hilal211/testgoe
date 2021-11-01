@extends('admin.layout.default')

@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>New Virtual Store</h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
						{!! Form::open(["id"=>"frmStoreOwner","name"=>"frmStoreOwner","url"=>route('admin.virtual-stores.store'),"method"=>"POST","enctype"=>'multipart/form-data']) !!}
							<div class="row">
								<div class="col-md-12">
									@include('errors.error')
								</div>
							</div>
							<div class="row setup-content" id="step-1">
								<div class="col-xs-12">
									<div class="">
										<h3>Virtual details</h3>
										<div class="form-group">
											@include('common.required_mark') {!! Form::label('store_name','Virtual Name',['class'=>'control-label']) !!}
											{!! Form::text('virtualname','',['class'=>'form-control','placeholder'=>'Enter Virtual Name']) !!}
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													@include('common.required_mark') {!! Form::label('address_1',trans('keywords.Enter Address 1'),['class'=>'control-label']) !!}
													{!! Form::text('address_1','',['class'=>'form-control','placeholder'=>trans('keywords.Street Address')]) !!}
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													{!! Form::label('address_2',trans('keywords.Enter Address 2'),['class'=>'control-label']) !!}
													{!! Form::text('address_2','',['class'=>'form-control','placeholder'=>trans('keywords.Apartment, Suite, Unit etc... (optional)')]) !!}
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													@include('common.required_mark') {!! Form::label('state',trans('keywords.Province / State'),['class'=>'control-label']) !!}
													{{ Form::select('state',$states,'',['class'=>'form-control select2','onchange'=>'getCities(this)']) }}
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													@include('common.required_mark') {!! Form::label('city',trans('keywords.City'),['class'=>'control-label']) !!}
													{{ Form::select('city',$cities,'',['class'=>'form-control select2','onchange'=>'CheckCity(this)']) }}
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													@include('common.required_mark') {!! Form::label('zip',trans('keywords.Postal / Zip Code'),['class'=>'control-label']) !!}
													{{ Form::text('zip','',['class'=>'form-control'])}}
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row setup-content" id="step-2">
								<div class="col-xs-12">
									<div class="">
										<h3>Basic details</h3>
										<div class="form-group">
											{{ Form::label('image','Profile Image',['class'=>'control-label']) }}
											<div class="col-md-12 input-group">
												{{ Form::file('profile_image', ['data-multiple'=>false,'class'=>'file-input']) }}
											</div>
											<span class="help-block">Please upload image with aspect ratio of 9:8 or preferably (45 px X 40 px) size.</span>
										</div>
										<div class="form-group">
											@include('common.required_mark') {!! Form::label('cat',trans('keywords.Do you offer the Home Delivery service?'),['class'=>'control-label']) !!}
											<div>
												<label class="radio-inline">
													{!! Form::radio('home_delivery','1',false,['class'=>'i-check']) !!} {{trans('keywords.Yes')}}
												</label>
												<label class="radio-inline">
													{!! Form::radio('home_delivery','0',false,['class'=>'i-check']) !!} {{trans('keywords.No')}}
												</label>
											</div>
										</div>
										<div class="form-group">
											@include('common.required_mark') {!! Form::label('contactnumber','Contact #',['class'=>'control-label']) !!}
											{!! Form::text('contactnumber','',['class'=>'form-control','placeholder'=>'Enter Contact #',"data-inputmask"=>'"mask": "(999) 999-9999"',"data-mask"=>""]) !!}
										</div>
										<div class="form-group">
											@include('common.required_mark') {!! Form::label('storetype',trans('keywords.What type of store you have?'),['class'=>'control-label']) !!}
											{{ Form::select('storetype',$types,'',['class'=>'form-control select2']) }}
										</div>
									</div>
								</div>
							</div>
							<div class="row setup-content" id="step-3">
								<div class="col-xs-12">
									<div class="">
										<h3>Legal details</h3>
										<div class="form-group">
											@include('common.required_mark') {!! Form::label('legal_entity_name',trans('keywords.Legal Entity Name'),['class'=>'control-label']) !!}
											{!! Form::text('legal_entity_name','',['class'=>'form-control','placeholder'=>trans('keywords.Enter Legal Entity Name')]) !!}
										</div>
										<div class="form-group">
											@include('common.required_mark') {!! Form::label('year',trans('keywords.Year of Establishment'),['class'=>'control-label']) !!}
											{!! Form::text('year','',['class'=>'form-control','placeholder'=>trans('keywords.Enter Year of Establishment')]) !!}
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													{!! Form::label('legal_address_1',trans('keywords.Enter Address 1'),['class'=>'control-label']) !!}
													{!! Form::text('legal_address_1','',['class'=>'form-control','placeholder'=>trans('keywords.Street Address')]) !!}
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													{!! Form::label('legal_address_2',trans('keywords.Enter Address 2'),['class'=>'control-label']) !!}
													{!! Form::text('legal_address_2','',['class'=>'form-control','placeholder'=>trans('keywords.Apartment, Suite, Unit etc... (optional)')]) !!}
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													@include('common.required_mark') {!! Form::label('legal_state',trans('keywords.Province / State'),['class'=>'control-label']) !!}
													{{ Form::select('legal_state',$states,'',['class'=>'form-control select2','onchange'=>'getCities(this)']) }}
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													@include('common.required_mark') {!! Form::label('legal_city',trans('keywords.City'),['class'=>'control-label']) !!}
													{{ Form::select('legal_city',$cities,'',['class'=>'form-control select2','onchange'=>'CheckCity(this)']) }}
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													@include('common.required_mark') {!! Form::label('legal_zip',trans('keywords.Postal / Zip Code'),['class'=>'control-label']) !!}
													{{ Form::text('legal_zip','',['class'=>'form-control'])}}
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													{!! Form::label('gst',trans('keywords.GST #'),['class'=>'control-label']) !!}
													{!! Form::text('gst','',['class'=>'form-control','placeholder'=>trans('keywords.Enter GST number')]) !!}
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													{!! Form::label('hst',trans('keywords.HST #'),['class'=>'control-label']) !!}
													{!! Form::text('hst','',['class'=>'form-control','placeholder'=>trans('keywords.Enter HST number')]) !!}
												</div>
											</div>
										</div>
										{{ Form::button(trans('keywords.Submit'),["type"=>"submit","class"=>"btn btn-primary pull-right"]) }}
									</div>
								</div>
							</div>
						<div class="gap gap-small gap-bottom"></div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@stop
@section('custom_js')


@stop