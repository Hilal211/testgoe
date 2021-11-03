@extends('admin.layout.default')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Analytics</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                        <div class="box-tools pull-right">
                            <!-- <a class="btn-block btn-box-tool" href="#CouponModal" data-toggle="modal"><i class="fa fa-plus"></i> Add announcement</a> -->
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="All_CouponTable" class="table no-margin table-actions table-small-padding orderTable">
                                <thead>
                                    <tr>
                                        <th>User Id</th>
                                        <th>User Email</th>
                                        <th>Step</th>
                                        <th>Date</th>

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
        {!! Form::open(["url"=>route('save.announcement'),"method"=>"POST",'onsubmit'=>'return SaveCoupon(this)']) !!}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Announcements</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger form-errors display-none">
                    <ul></ul>
                </div>
                <div class="form-group">
                    @include('common.required_mark') {{ Form::label('description','Description',['class'=>'control-label']) }}
                    {{ Form::text('description','',["class"=>'form-control','placeholder'=>'Description',"spellcheck"=>"true"]) }}
                </div>
                <div class="form-group">
                    @include('common.required_mark') {{ Form::label('english_image','English Image',['class'=>'control-label']) }}
                    {{ Form::file('english_image', ['data-multiple'=>false,'class' => 'file-input','id'=>'english_image']) }}
                </div>
                <div class="form-group">
                    @include('common.required_mark') {{ Form::label('francais_image','french Image',['class'=>'control-label']) }}
                    {{ Form::file('francais_image', ['data-multiple'=>false,'class' => 'file-input','id'=>'francais_image']) }}
                </div>
                <div class="form-group">
                    @include('common.required_mark') {!! Form::label('status','status',['class'=>'control-label']) !!}
                    <div>
                        <label class="">
                            {!! Form::radio('status','1',false,['class'=>'i-radio image_status']) !!}Yes &nbsp;
                        </label>
                        <label class="">
                            {!! Form::radio('status','0',false,['class'=>'i-radio image_status']) !!} No &nbsp;
                        </label>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
                {{ Form::hidden('id','0',['id'=>'id']) }}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@stop
@section('custom_js')
<script>
    var oTable = ""
    $(document).ready(function() {
        var date = new Date();
        $('input.coupon_type').on('ifChanged', function(event) {
            TypeToggle(this);
        });
        oTable = $('#All_CouponTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: 'statistics-data',
            order: [],
            "drawCallback": function(settings) {
                InitTooltip();
            },
            columns: [{
                    data: '0',
                    name: 'user_id'
                },
                {
                    data: '8',
                    name: 'email',
                    searchable: false,
                    sortable: false
                },
                {
                    data: '2',
                    name: 'step',
                    searchable: false,
                    sortable: false
                },
                {
                    data: '1',
                    name: 'date',
                    searchable: false,
                    sortable: false
                },
               

            ]
        });
        $('#CouponModal').on('hidden.bs.modal', function() {
            console.log('hode');
            var Form = $(this).find('form')
            $(Form).find('input[name="id"]').val('0');
            $(Form).find('input[name="description"]').val('');
            $("#code").removeAttr('readOnly');

            $(Form).find('input:radio').iCheck('uncheck');
            $(Form).find('input:radio').iCheck('update');

            $("#english_image").fileinput('clear');

            $("#francais_image").fileinput('clear');

            $(Form).find('.form-errors').hide();
            $(Form).find('.form-errors').hide();
            $('#inactive-section').hide();
        })
    })
    function showEdit(element) {
        var Form = $('#CouponModal').find('form')
        var Id = $(element).attr('data-id');
        var APP_URL = $('meta[name="_base_url"]').attr('content');
        var URL = APP_URL + '/admin/announcement/' + Id + '/edit';
        $.ajax({
            type: "GET",
            url: URL,
            data: {},
            dataType: "json",
            success: function(res) {
                $('#CouponModal').modal('show');
                $('#inactive-section').show();
                SetFormValues(res.inputs, Form);
                $("#english_image").fileinput('destroy');
                if (res.inputs.english_image.file != '') {
                    $("#english_image").fileinput('refresh', {
                        'initialPreview': [
                            '<img src="' + res.inputs.english_image.file + '" class="file-preview-image" width="auto">'
                        ],
                        'initialPreviewAsData': true,
                        'overwriteInitial': true,
                        'showUpload': false,
                        'showRemove': false,
                    });
                    $('.kv-file-remove').prop('disabled', false).removeClass('disabled');
                    $('.fileinput-remove').addClass('hide');
                } else {
                    $("#english_image").fileinput('clear');
                    $("#english_image").fileinput('refresh', {
                        showUpload: false,
                        showRemove: false,
                    });
                }

                $("#francais_image").fileinput('destroy');
                if (res.inputs.francais_image.file != '') {
                    $("#francais_image").fileinput('refresh', {
                        'initialPreview': [
                            '<img src="' + res.inputs.francais_image.file + '" class="file-preview-image" width="auto">'
                        ],
                        'initialPreviewAsData': true,
                        'overwriteInitial': true,
                        'showUpload': false,
                        'showRemove': false,
                    });
                    $('.kv-file-remove').prop('disabled', false).removeClass('disabled');
                    $('.fileinput-remove').addClass('hide');
                } else {
                    $("#francais_image").fileinput('clear');
                    $("#francais_image").fileinput('refresh', {
                        showUpload: false,
                        showRemove: false,
                    });
                }

                $("#code").attr('readOnly', 'true');
            },
        })
    }

    function SaveCoupon(form) {
        var options = {
            target: '',
            url: $(form).attr('action'),
            type: 'POST',
            success: function(res) {
                $('#CouponModal').modal('hide');
                oTable.draw(true);
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