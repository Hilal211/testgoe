@extends('admin.layout.default')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>Profile</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-10">
        <div class="box box-info">
          <div class="box-header">

          </div>
          <div class="box-body">
            @include('errors.error')
            {!! Form::open(["url"=>route("save.profile"),"method"=>"POST"]) !!}
              <div class="form-group">
                @include('common.required_mark') {!! Form::label('email','Email',['class'=>'control-label']) !!}
                {!! Form::text('email',@$user->email,['class'=>'form-control','placeholder'=>'Email Address']) !!}
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    {!! Form::label('password','Password',['class'=>'control-label']) !!}
                    {!! Form::password('password',['class'=>'form-control','placeholder'=>'Enter Password']) !!}
                    <span class="help-block">Leave password fields blank if you don't want to change password</span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    {!! Form::label('password_confirmation','Confirm Password',['class'=>'control-label']) !!}
                    {!! Form::password('password_confirmation',['class'=>'form-control','placeholder'=>'Retype Password']) !!}
                  </div>
                </div>
              </div>
              {{ Form::button('Save',["type"=>"submit","class"=>"btn btn-primary nextBtn pull-right"]) }}
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@stop
@section('left_sidebar')

@stop
@section('custom_js')

@stop
