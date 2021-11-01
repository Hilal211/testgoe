@extends('admin.layout.default')
@section('content')    
<div class="content-wrapper">
  <section class="content-header">
    <h1>Coupons</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"></h3>
            <div class="box-tools pull-right">
              <a class="btn-block btn-box-tool" href="#CouponModal" data-toggle="modal"><i class="fa fa-plus"></i> Add Coupon</a>
              </div>
            </div>
          <div class="box-body">
            <div class="table-responsive">
              <table id="All_CouponTable" class="table no-margin table-actions table-small-padding orderTable">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Code</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Used</th>
                    <th>Value</th>
                  </tr>
                </thead>              
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div id="CouponModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    {!! Form::open(["url"=>route('admin.coupons.store'),"method"=>"POST",'onsubmit'=>'return SaveCoupon(this)']) !!}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Coupons</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger form-errors display-none">
          <ul></ul>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              @include('common.required_mark') {!! Form::label('type','Type',['class'=>'control-label']) !!}
              <div>
                <label class="">
                  {!! Form::radio('type','1',false,['class'=>'i-radio coupon_type']) !!} Postal / Zip Code &nbsp;
                </label>
                <label class="">
                  {!! Form::radio('type','2',false,['class'=>'i-radio coupon_type']) !!} Stores &nbsp;
                </label>
                <label class="">
                  {!! Form::radio('type','3',false,['class'=>'i-radio coupon_type']) !!} Any
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                @include('common.required_mark') {!! Form::label('value_type','Value Type',['class'=>'control-label']) !!}
                <div>
                  <label class="">
                    {!! Form::radio('value_type','1',false,['class'=>'i-radio']) !!} Flat &nbsp;
                  </label>
                  <label class="">
                    {!! Form::radio('value_type','2',false,['class'=>'i-radio']) !!} Percentage &nbsp;
                  </label>
                </div>
              </div>
          </div>
          <div id="inactive-section" class="col-md-4 form-group no-padding" style="display: none">
            <label>&nbsp;</label>
            <label class="col-md-12 no-padding">
              {{ Form::checkbox('is_inactive','0','',["class"=>'i-check']) }} &nbsp; In Active
            </label>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-4">
            @include('common.required_mark') {{ Form::label('value','Coupon Value',['class'=>'control-label']) }}
            {{ Form::text('value','',["class"=>'form-control']) }}
          </div>
          <div class="col-md-4">
            @include('common.required_mark') {{ Form::label('value','Min. Order Total',['class'=>'control-label']) }}
            {{ Form::text('min_order_amount','',["class"=>'form-control']) }}
          </div>
          <div class="col-md-4">
            @include('common.required_mark') {{ Form::label('value','Coupon use limit',['class'=>'control-label']) }}
            {{ Form::text('coupon_limit','',["class"=>'form-control','placeholder'=>'limit of coupon use per user']) }}
          </div>
        </div>
        <div id="type_zip" class="form-group" {!! (old('type') == 1) ? "" : 'style="display: none"' !!}>
          @include('common.required_mark') {!! Form::label('zip','Postal / Zip Code',['class'=>'control-label']) !!}
          {{ Form::select('zips[]',$zips,'', ['class'=>'select2 form-control','data-placeholder'=>"Select",'multiple'=>true,"id"=>'zip_select']) }}
        </div>
        <div id="store_individual_type" {!! (old('type') == 2) ? "" : 'style="display: none"' !!}>
          <div class="form-group">
            @include('common.required_mark') {!! Form::label('stores','Stores',['class'=>'control-label']) !!}
            {{ Form::select('stores[]',$stores,'',["id"=>'stores','class'=>'form-control select2','multiple'=>'true','id'=>'store_select']) }}
          </div>
        </div>
        {{-- <div id="type_order_amount" {!! (old('type') == 3) ? "" : 'style="display: none"' !!}>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                @include('common.required_mark') {!! Form::label('amount_type','Amount Condition',['class'=>'control-label']) !!}
                <select class="form-control" name="amount_condition">
                  <option value="1">Less than order amount</option>
                  <option value="2">Greater than order amount</option>
                  <option value="3">Equal to order amount</option>
                </select>
              </div>  
            </div>
            <div class="col-md-6">
              <div class="form-group">
                @include('common.required_mark') {!! Form::label('amount','Amount',['class'=>'control-label']) !!}
                {{ Form::text('amount','',['class'=>'form-control','placeholder'=>'Order Amount'])}}
              </div>
            </div>
          </div>
        </div> --}}
        <div class="form-group">
          @include('common.required_mark') {{ Form::label('code','Code',['class'=>'control-label']) }}
          {{ Form::text('code','',["class"=>'form-control','placeholder'=>'E.g FIFTYOFF']) }}
        </div>
        <div class="form-group">
            {{ Form::label('image','Image',['class'=>'control-label']) }}
            {{ Form::file('image', ['data-multiple'=>false,'class' => 'file-input','id'=>'coupon-image']) }}
        </div>
        <div class="row form-group">
          <div class="col-md-6">
            @include('common.required_mark') {{ Form::label('start_date','Start Date',['class'=>'control-label']) }}
            {{ Form::text('start_date','',["class"=>'start_date_picker form-control','autocomplete'=>'off']) }}
          </div>
          <div class="col-md-6">
            @include('common.required_mark') {{ Form::label('end_date','End Date',['class'=>'control-label']) }}
            {{ Form::text('end_date','',["class"=>'end_date_picker form-control','autocomplete'=>'off']) }}
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
        {{ Form::hidden('id','0',['id'=>'coupon_id']) }}
      </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>

@stop
@section('custom_js')
<script>
  var oTable = ""
  $(document).ready(function(){
    var date = new Date();
    $('input.coupon_type').on('ifChanged', function(event){
      TypeToggle(this);
    });
    $('.start_date_picker').datepicker({
        format: "d M yyyy",
        autoclose: true,
        startDate: date
    }).on('changeDate', function(e) {
        var element = e.target; 
        var value  = $(element).val();
        $('.end_date_picker').datepicker('remove');
        $('.end_date_picker').val('');
        $('.end_date_picker').datepicker({
          format: "d M yyyy",
          autoclose: true,
          startDate: value
        });
    });
    oTable = $('#All_CouponTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: 'coupon-data',
      order: [],
      "drawCallback": function( settings ) {
        InitTooltip();
      },
      columns: [
          {data: '0', name: 'type',searchable:false,sortable:false},
          {data: '1', name: 'code'},
          {data: '2', name: 'start_date'},
          {data: '3', name: 'end_date'},
          {data: '4', name: 'used_count'},
          {data: '6', name: 'value',searchable:false,sortable:false},
      ]
    });
    $('#CouponModal').on('hidden.bs.modal', function () {
      console.log('hode');
      var Form = $(this).find('form')
      $(Form).find('input[name="id"]').val('0');
      $(Form).find('input[name="amount"]').val('');
      $(Form).find('input[name="code"]').val('');
      $(Form).find('input[name="min_order_amount"]').val('');
      $(Form).find('input[name="coupon_limit"]').val('');
      $(".start_date_picker").datepicker("setDate", '');
      
      $(".start_date_picker").removeAttr('readOnly');
      $(".end_date_picker").removeAttr('readOnly');
      $("#code").removeAttr('readOnly');

      $(Form).find('input[name="value"]').val('');
      $(Form).find('input[name="amount"]').val('');
      
      $('.end_date_picker').datepicker('remove');
      $('.end_date_picker').val('');
      $(Form).find('select[name="amount_condition"]').val('1').change();
      $(Form).find('#zip_select').val('').change();
      $(Form).find('#store_select').val('').change();

      $(Form).find('input:radio').iCheck('uncheck');
      $(Form).find('input:radio').iCheck('update');
      
      TypeToggle('','clear');
      $("#coupon-image").fileinput('clear');

      $(Form).find('.form-errors').hide();
      $(Form).find('.form-errors').hide();

      $(Form).find('input:checkbox').iCheck('uncheck');
      $(Form).find('input:checkbox').iCheck('update');

      $('#inactive-section').hide();
    })
  })
  function TypeToggle(element,type=""){
    if(type == ''){
        if(element.value == '1') {
          $('#type_zip').show();
          $('#store_individual_type').hide();
          $('#type_order_amount').hide();
        }else if(element.value == '2') {
          $('#type_zip').hide();
          $('#store_individual_type').show();
          $('#type_order_amount').hide();
        }else if(element.value == '3') {
          $('#type_zip').hide();
          $('#store_individual_type').hide();
          $('#type_order_amount').hide();
        }
    }
    else{
      $('#type_zip').hide();
      $('#store_individual_type').hide();
      $('#type_order_amount').hide();
    }
  }
  function showEdit(element){
    var Form = $('#CouponModal').find('form')
    var Id = $(element).attr('data-id');
    var APP_URL = $('meta[name="_base_url"]').attr('content');
    var URL = APP_URL+'/admin/coupons/'+Id+'/edit';
    $.ajax({
      type: "GET",
      url: URL,
      data: {},
      dataType: "json",
      success: function(res) {
          $('#CouponModal').modal('show');
          $('#inactive-section').show();
          SetFormValues(res.inputs,Form);

          $(".start_date_picker").datepicker("destroy");
          $(".end_date_picker").datepicker("destroy");

          $(".start_date_picker").val(res.inputs.start_date.value).attr('readOnly','true');
          $(".end_date_picker").val(res.inputs.end_date.value).attr('readOnly','true');
          
          //$(".start_date_picker").datepicker("setDate", res.inputs.start_date.value);
          //$(".end_date_picker").datepicker("setDate", res.inputs.end_date.value);

          $("#coupon-image").fileinput('destroy');
          if(res.inputs.image.file!=''){
            $("#coupon-image").fileinput('refresh',{
              'initialPreview': [
              '<img src="'+res.inputs.image.file+'" class="file-preview-image" width="auto">'
              ],
              'initialPreviewAsData': true,
              'overwriteInitial': true,
              'showUpload': false,
              'showRemove': false,
            });
            $('.kv-file-remove').prop('disabled',false).removeClass('disabled');
            $('.fileinput-remove').addClass('hide');
          }else{
            $("#coupon-image").fileinput('clear');
            $("#coupon-image").fileinput('refresh',{
              showUpload: false,
              showRemove: false,
            });
          }

          $("#code").attr('readOnly','true');
      },
    })
  }
  function SaveCoupon(form){
    var options = {
      target: '',
      url: $(form).attr('action'),
      type: 'POST',
      success: function(res) {
        $('#CouponModal').modal('hide');
        oTable.draw(true);
      },
      error: function (jqXHR, exception) {
        var Response = jqXHR.responseText,
        ErrorBlock = $(form).find('.form-errors'),
        Response = $.parseJSON(Response);
        DisplayErrorMessages(Response,ErrorBlock,'ul');
      }
    }
    $(form).ajaxSubmit(options);
    return false;
  }
</script>
@stop