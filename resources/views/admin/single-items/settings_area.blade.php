<div class="box box-info collapsed-box">
	<div class="box-header">
		<h3 class="box-title">Commision</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
	<div class="box-body">
		@if(count($errors->get($area->slug))>0)
			@include('errors.error')
		@endif
		{!! Form::open(["url"=>route("save.settings",['slug'=>$area->slug]),"method"=>"POST"]) !!}
		@if($area->icon=='')
			<div class="form-group">
				@include('common.required_mark') {!! Form::label($area->slug,$area->area,['class'=>'control-label']) !!}
				{!! Form::text($area->slug,$area->value,['class'=>'form-control','placeholder'=>'Enter '.$area->area.'']) !!}
			</div>
		@else
			@include('common.required_mark') {!! Form::label($area->slug,$area->area,['class'=>'control-label']) !!}
			<div class="form-group input-group">
				<span class="input-group-addon"><i class="{{$area->icon}}"></i></span>
				{!! Form::text($area->slug,$area->value,['class'=>'form-control','placeholder'=>'Enter '.$area->area.'']) !!}
			</div>
		@endif
		{{ Form::button('Save',["type"=>"submit","class"=>"btn btn-primary pull-right"]) }}
		{!! Form::close(); !!}
	</div>
</div>