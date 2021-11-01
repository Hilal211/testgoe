@extends('frontend.layout.default')

@section('content')
<div class="container">
	<div class="gap gap-small"></div>
	<div class="row bg-orange-rounded">
		<div class="registration-bg form-group">
			<div class="col-md-12">
				<h1 class="widget-title text-center">{{trans('keywords.Contact US')}}</h1>
				<p class="description text-center">{{trans('keywords.For more info and support, contact us!')}}</p>
			</div>
		</div>
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-12">
					@include('common.message')
					@include('errors.error')
				</div>
				{!! Form::open(["url"=>LaravelLocalization::getLocalizedURL(App::getLocale(),route('frontend.contact-us')),"method"=>"POST"]) !!}
					<div class="col-md-12">
						<div class="form-group">
							@include('common.required_mark') {{ Form::label('name',trans('keywords.Name')) }}
							{{ Form::text('name','',["class"=>'form-control',"placeholder"=>trans('keywords.Enter Name')]) }}
						</div>
						<div class="form-group">
							@include('common.required_mark') {{ Form::label('email',trans('keywords.Email')) }}
							{{ Form::text('email','',["class"=>'form-control',"placeholder"=>trans('keywords.Enter Email')]) }}
						</div>
						<div class="form-group">
							@include('common.required_mark') {{ Form::label('message',trans('keywords.Message')) }}
							{{ Form::textarea('message','',["class"=>'form-control',"placeholder"=>trans('keywords.Enter Message')]) }}
						</div>
						<button class="btn btn-primary nextBtn pull-right" type="submit" >{{trans('keywords.Submit')}}</button>
					</div>
					<div class="gap gap-small gap-bottom"></div>
				{!! Form::close() !!}
			</div>
		</div>
		<div class="col-md-4">
			<h3>&nbsp;</h3>
			<div>
				{{-- <h3>
					<span class="contact-box"><i class="fa fa-map-marker"></i></span> <p class="contact-box-text">{{trans('keywords.3604 ste famille Montreal H2X 2L4 Qc, Canada')}}</p>
				</h3> --}}
				<h3>
					<span class="contact-box"><i class="fa fa-envelope"></i></span> <p class="contact-box-text"><a href="mailto:info@Goecolo.com">Info@Goecolo.com</a></p>
				</h3>
				{{-- <h3>
					<span class="contact-box"><i class="fa fa-phone"></i></span> <p class="contact-box-text">{{trans('keywords.+15144432199')}}</p>
				</h3> --}}
			</div>
		</div>
	</div>
</div>
@stop