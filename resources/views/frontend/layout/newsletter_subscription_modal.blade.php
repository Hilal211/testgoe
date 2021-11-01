<div class="mfp-with-anim mfp-hide mfp-dialog clearfix" id="nav-subscribe-dialog">
    <h3 class="widget-title">{{trans('keywords.Subscribe')}}</h3>
    <hr />
    @include('errors.error')
    <div class="alert alert-danger form-errors display-none"><ul></ul></div>
	{!! Form::open(["url"=>LaravelLocalization::getLocalizedURL(App::getLocale(),route('post.subscription')),"method"=>"POST","onsubmit"=>'return Subscribe(this)']) !!}
	<div class="form-group">
	    @include('common.required_mark') {{ Form::label('email',trans('keywords.Email')) }}
	    {{ Form::text('email','',["class"=>'form-control',"placeholder"=>trans('keywords.Email')]) }}
	</div>
	<div class="form-group">
	    @include('common.required_mark') {{ Form::label('zip',trans('keywords.Postal / Zip Code')) }}
	    {{ Form::select('zip',$zips,'',['class'=>'form-control select2',"id"=>"zip-select"]) }}
	</div>
	{{ Form::button(trans('keywords.Submit'),["type"=>"submit","class"=>"btn btn-primary"]) }}
	{!! Form::close() !!}
</div>