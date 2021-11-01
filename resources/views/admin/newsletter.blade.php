@extends('admin.layout.default')
@section('content')    
<div class="content-wrapper">
  <section class="content-header">
    <h1>Newsletter</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        @include('errors.error')
        <div class="box box-info">
          <div class="box-body">
            @include('common.message')
            {!! Form::open(["url"=>route("send.newsletter"),"method"=>"POST"]) !!}
              <div class="form-group">
                @include('common.required_mark') {!! Form::label('to','Send to',['class'=>'control-label']) !!}
                <div>
                  <label class="">
                    {!! Form::radio('to','1',false,['class'=>'i-radio send_to_type']) !!} Customers &nbsp;
                  </label>
                  <label class="">
                    {!! Form::radio('to','2',false,['class'=>'i-radio send_to_type']) !!} Stores &nbsp;
                  </label>
                  <label class="">
                    {!! Form::radio('to','3',false,['class'=>'i-radio send_to_type']) !!} Guests
                  </label>
                </div>
              </div>
              <div id="customer_types" class="form-group" {!! (old('to') == 1) ? "" : 'style="display: none"' !!}>
                @include('common.required_mark') {!! Form::label('cust_type','Criteria',['class'=>'control-label']) !!}
                <div>
                  <label class="">
                    {!! Form::radio('cust_type','1',false,['class'=>'i-radio customer_to_type']) !!} Distance based &nbsp;
                  </label>
                  <label class="">
                    {!! Form::radio('cust_type','2',false,['class'=>'i-radio customer_to_type']) !!} Customers
                  </label>
                </div>
              </div>

              <div id="customer_distance_type" {!! (old('cust_type') == 1 && old('to') == 1) ? 'style="display: block"' : 'style="display: none"' !!}>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-8">
                            @include('common.required_mark') {!! Form::label('zip','Postal / Zip Code',['class'=>'control-label']) !!}
                          </div>
                          <div class="col-md-4">
                             {{-- <span class="text-right font-12"><i class="fa fa-exclamation-circle"></i> &nbsp;Press Esc to close list.</span> --}}
                          </div>
                        </div>
                        {{ Form::select('zips[]',$zips,'', ['class'=>'select2-multi form-control','data-placeholder'=>"Select",'multiple'=>true]) }}
                      </div>
                    </div>
                  </div>
              </div>
              <div id="customer_individual_type" {!! (old('cust_type') == 2 && old('to') == 1) ? 'style="display: block"' : 'style="display: none"' !!}>
                  <div class="form-group">
                    @include('common.required_mark') {!! Form::label('to_customers','Individual customers',['class'=>'control-label']) !!}
                    {{ Form::select('to_customers[]',$customers,'',['class'=>'form-control select2','multiple'=>'true']) }}
                  </div>
              </div>
              <div id="store_individual_type" {!! (old('to') == 2) ? "" : 'style="display: none"' !!}>
                <div class="row">
                  {{-- <div class="col-md-2 m-top-15">
                    <button class="btn btn-primary btn-sm" type="button" onclick="selectAll()">All</button>
                    <button class="btn btn-danger btn-sm" type="button" onclick="reset()">Reset</button>
                  </div> --}}
                  <div class="col-md-12">
                    <div class="form-group">
                      @include('common.required_mark') {!! Form::label('to_stores','Stores',['class'=>'control-label']) !!}
                      {{ Form::select('to_stores[]',$stores,'',["id"=>'to_stores','class'=>'form-control select2','multiple'=>'true']) }}
                    </div>
                  </div>
                </div>
              </div>
              <div id="guest_distance_type" {!! (old('to') == 3) ? "" : 'style="display: none"' !!}>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-8">
                            @include('common.required_mark') {!! Form::label('zip','Boroughs Postal Codes',['class'=>'control-label']) !!}
                          </div>
                          <div class="col-md-4">
                            {{-- <span class="text-right font-12"><i class="fa fa-exclamation-circle"></i> &nbsp;Press Esc to close list.</span> --}}
                          </div>
                        </div>
                        {{ Form::select('guest_zips[]',$zips,'', ['class'=>'select2-multi form-control','data-placeholder'=>"Select",'multiple'=>true]) }}
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                @include('common.required_mark') {!! Form::label('subject','Subject',['class'=>'control-label']) !!}
                {{ Form::text('subject','',['class'=>'form-control'])}}
              </div>
              <div class="form-group">
                @include('common.required_mark') {!! Form::label('body','Email Body',['class'=>'control-label']) !!}
                {{ Form::textarea('body','',['class'=>'form-control','id'=>'email-body'])}}
              </div>
              {{ Form::button('Send',["type"=>"submit","class"=>"btn btn-primary pull-right"]) }}
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@stop
@section('custom_js')
<script type="text/javascript">
  $(document).ready(function(){
    $('.select2-multi').select2({
        placeholder: SelectPlaceHolder,
        allowClear: true,
        //closeOnSelect: false
    })
    $('#email-body').summernote({
      height: 200,
      callbacks: {
        onImageUpload: function(files,editor, welEditable) {
          console.log('aaaa');
          sendFile(files[0], editor, welEditable);
        }
      }
    });
  })
  $('input.send_to_type').on('ifChecked', function(event){
    sendToToggle(this);
  });
  $('input.customer_to_type').on('ifChecked', function(event){
    customerToggle(this);
  });

  function selectAll(){
    $("#to_stores > option").prop("selected",true);
    $("#to_stores").trigger("change");
  }
  function sendFile(file, editor, welEditable) {
    data = new FormData();
    data.append("file", file);
    console.log(data);
    $.ajax({
      data: data,
      type: "POST",
      url: "{{route('save.newsletter.photo')}}",
      cache: false,
      contentType: false,
      processData: false,
      success: function(url) {
        //editor.insertImage(welEditable, url);
        $('#email-body').summernote('insertImage', url, 'filename.jpg');
      }
    });
  }
  function reset(){
    $("#to_stores > option").removeAttr("selected");
    $("#to_stores").trigger("change"); 
  }
  function sendToToggle(element){
    if(element.value == '1') {
      $('#customer_types').show();
      $('#store_individual_type').hide();
      $('#guest_distance_type').hide();
    } else if(element.value == '2'){
      $('input.customer_to_type').removeAttr('checked').iCheck('update');      
      $('#customer_types').hide();
      $('#customer_distance_type').hide();
      $('#customer_individual_type').hide();
      $('#store_individual_type').show();
      $('#guest_distance_type').hide();
    } else if(element.value == '3'){
      $('input.customer_to_type').removeAttr('checked').iCheck('update');
      $('#customer_types').hide();
      $('#customer_distance_type').hide();
      $('#customer_individual_type').hide();
      $('#store_individual_type').hide();
      $('#guest_distance_type').show();
    }
  }
  function customerToggle(element){
    if(element.value == '1') {
      $('#customer_distance_type').show();
      $('#customer_individual_type').hide();
    } else if(element.value == '2'){
      $('#customer_distance_type').hide();
      $('#customer_individual_type').show();
    }
  }
</script>
@stop