@extends('admin.layout.default') @section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Virtual Stores</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                        <div class="box-tools pull-right">
                            <a class="btn btn-box-tool" href="{{route('admin.virtual-stores.create')}}"><i class="fa fa-plus"></i> Add new</a>
                            <a class="btn btn-box-tool" href="#BankDetailsModal" data-toggle="modal"><i class="fa fa-usd"></i> Bank detail</a>
                        </div>
                    </div>
                    <div class="box-body">
                        @include('common.message')
                        <div class="table-responsive">
                            <table id="virtual_storetable" class="table table-bordered table-actions table-striped">
                                <thead>
                                    <tr>
                                        <th>Store Name</th>
                                        <th>Home Delivery</th>
                                        <th>Payment Status</th>
                                        <th>Category</th>
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
<div id="BankDetailsModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Virtual Store Bank Details</h4>
            </div>
            {!! Form::open(["url"=>route('post.save.virtual.bank'),"method"=>"POST",'id'=>'account_details_form',"enctype"=>'multipart/form-data','onsubmit'=>'return ApproveBank(this)']) !!}
            <div class="modal-body">
                <div class="alert alert-danger form-errors display-none">
                    <ul></ul>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            @include('common.required_mark') {{ Form::label('email','Email') }} {{ Form::text('email','',['class'=>'form-control']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            @include('common.required_mark') {{ Form::label('firstname','Firstname') }} {{ Form::text('firstname','',['class'=>'form-control']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            @include('common.required_mark') {{ Form::label('lastname','Lastname') }} {{ Form::text('lastname','',['class'=>'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            @include('common.required_mark') {!! Form::label('add1','Address 1',['class'=>'control-label']) !!} {!! Form::text('add1','',['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('add2','Address 2',['class'=>'control-label']) !!} {!! Form::text('add2','',['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            @include('common.required_mark') {!! Form::label('state','Province / State',['class'=>'control-label']) !!} {{ Form::select('state',$states,'',['class'=>'form-control select2','onchange'=>'getCities(this)']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            @include('common.required_mark') {!! Form::label('city','City',['class'=>'control-label']) !!} {{ Form::select('city',$cities,'',['class'=>'form-control select2','onchange'=>'CheckCity(this)']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            @include('common.required_mark') {!! Form::label('zip','Postal / Zip Code',['class'=>'control-label']) !!} {{ Form::text('zip','',['class'=>'form-control'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            @include('common.required_mark') {{ Form::label('dob','Date Of Birth') }} {{ Form::text('dob','',['class'=>'form-control date-picker']) }}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            @include('common.required_mark') {{ Form::label('document','Verification document',['class'=>'control-label']) }}
                            <div class="col-md-12 input-group">
                                {{ Form::file('document',['id'=>'document-file','data-multiple'=>false,'class'=>'file-input']) }}
                                <span class="help-block">Any goverment approved photo ID, E.g. Driver's License / Passport etc..</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            @include('common.required_mark') {!! Form::label('account_holder','Account Holder Name',['class'=>'control-label']) !!} {!! Form::text('account_holder_name','',['class'=>'form-control','placeholder'=>'Enter Account Holder Name']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            @include('common.required_mark') {!! Form::label('bank_name','Bank Name',['class'=>'control-label']) !!} {!! Form::text('bank_name','',['class'=>'form-control','placeholder'=>'Enter Bank Name']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            @include('common.required_mark') {!! Form::label('account_number','Account Number',['class'=>'control-label']) !!} {!! Form::text('account_number','',['class'=>'form-control','placeholder'=>'Enter Account Number']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            @include('common.required_mark') {!! Form::label('business_name','Business Name',['class'=>'control-label']) !!} {!! Form::text('business_name','',['class'=>'form-control','placeholder'=>'Enter Business Name']) !!}
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            @include('common.required_mark') {!! Form::label('routing_number','Transit Number',['class'=>'control-label']) !!} {!! Form::text('routing_number','',['class'=>'form-control','placeholder'=>'Enter Routing Number']) !!}
                            <span class="help-block">You can find it on your check. Please enter in the format "BBBBB-AAA" where AAA is three digits code identifying the institution and BBBBB is five digits code identifying the branch.</span> {{ Form::hidden('stripe_account_id','') }} {{ Form::hidden('id','') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-12">
                        {{ Form::checkbox('agreement','1',(@$bank_details->tos_acceptance_ip!='' ? true : false),["class"=>'i-check']) }} By registering your account, you agree to our <a href="">Services Agreement</a> and the <a href="https://stripe.com/connect-account/legal" target="_blank">Stripe Connected Account Agreement</a>.
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {{ Form::button('Save',["type"=>"submit","class"=>"btn btn-primary pull-right"]) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@stop @section('custom_js')
<script>
    $(document).ready(function(){
        $('#BankDetailsModal').on('hidden.bs.modal', function () {
            var form = $(this).find('form');
            $(form).find('input').prop('readonly',false);
            $(form).find('button[type="submit"]').prop('disabled',false);
            $(form).find('select').select2().enable(true);
            $("#document-file").fileinput('clear');
            $("#document-file").fileinput('refresh',{
                showUpload: false,
                showRemove: false,
            });
            $(form).find('input[type="checkbox"]').iCheck('enable');
            $(Form).find('.form-errors').hide();
        });
        $('#BankDetailsModal').on('show.bs.modal', function () {
            var form = $(this).find('form');
            getBankDetails(form);
        });
    })
    var oTable = ""
    $(document).ready(function(){
        oTable = $('#virtual_storetable').DataTable({
            processing: true,
            serverSide: true,
            ajax: 'virtual-store-data',
            order: [],
            "drawCallback": function( settings ) {
                InitTooltip(); 
            }
        });
    })
    function getBankDetails(form){
        var APP_URL = '{{route('get.virtual.bank')}}';
        $.ajax({
            type: "GET",
            url: APP_URL,
            data: {},
            dataType: "json",
            success: function(res) {
                if(res.inputs.length != 0){
                    SetFormValues(res.inputs,form);
                    $("#document-file").fileinput('destroy');
                    if(res.inputs.document.value!=''){
                      $("#document-file").fileinput('refresh',{
                        'initialPreview': [
                        '<img src="'+res.inputs.document.value+'" class="file-preview-image" width="auto">'
                        ],
                        'initialPreviewAsData': true,
                        'overwriteInitial': true,
                        'showUpload': false,
                        'showRemove': false,
                    });
                      $('.kv-file-remove').prop('disabled',false).removeClass('disabled');
                      $('.fileinput-remove').addClass('hide');
                  }else{
                      $("#document-file").fileinput('clear');
                      $("#document-file").fileinput('refresh',{
                        showUpload: false,
                        showRemove: false,
                    });
                  }
                  $(form).find('input').prop('readonly',true);
                  $(form).find('button[type="submit"]').prop('disabled',true);
                  $(form).find('select').select2().enable(false);
                  $("#document-file").fileinput("disable").fileinput("refresh", {showUpload: false});
                  $(form).find('input[type="checkbox"]').iCheck('disable');
                }
            },
      });
    }
    function ApproveBank(form) {
        var options = {
            target: '',
            url: $(form).attr('action'),
            type: 'POST',
            success: function(res) {
                $(form).parents('.modal').modal('hide');
            },
            error: function(jqXHR, exception) {
                var Response = jqXHR.responseText,
                    ErrorBlock = $(form).find('.form-errors'),
                    Response = $.parseJSON(Response);
                DisplayErrorMessages(Response, ErrorBlock, 'ul');
            }
        }
        $(form).ajaxSubmit(options);
        return false;
    }
</script>
@stop