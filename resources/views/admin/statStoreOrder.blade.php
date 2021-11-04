@extends('admin.layout.default')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>state store order</h1>
    </section>
    <section class="content">
        {!! Form::open(["url"=>route('save.statorderstore'),"method"=>"POST",'onsubmit'=>'return SaveCoupon(this)']) !!}
        <div class="row form-group">
            <div class="col-md-3">
                {{ Form::label('start_date','Start Date',['class'=>'control-label']) }}
                {{ Form::text('start_date','',["class"=>'start_date_picker form-control','autocomplete'=>'off']) }}
            </div>
            <div class="col-md-3">
                {{ Form::label('end_date','End Date',['class'=>'control-label']) }}
                {{ Form::text('end_date','',["class"=>'end_date_picker form-control','autocomplete'=>'off']) }}
            </div>
            <div class="col-md-3">
                {{ Form::label('store','store',['class'=>'control-label']) }}
                <select name="store" id="store" class=' form-control'>
                    <option value="all">All</option>
                    @foreach($data as $d)
                    <option value="{{$d->id}}">{{$d->storename}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary" style="margin-top: 24px;">Done</button>
            </div>
        </div>
        {!! Form::close() !!}
        <div class="countPro" style="display:none">this product is order <span class="productCount"></span></div>
        <div class="intro">Please do your selection</div>
        <div class="row products" style="display:none">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                        <!-- <div class="box-tools pull-right">
                            <a class="btn-block btn-box-tool" href="#CouponModal" data-toggle="modal"><i class="fa fa-plus"></i> Add announcement</a>
                        </div> -->
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="All_CouponTable" class="table no-margin table-actions table-small-padding orderTable">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Total</th>

                                    </tr>
                                </thead>
                                <tbody class="data-container">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
        $('.start_date_picker').datepicker({
            format: "yyyy-mm-d",
            autoclose: true,
            // startDate: date
        }).on('changeDate', function(e) {
            var element = e.target;
            var value = $(element).val();
            $('.end_date_picker').datepicker('remove');
            $('.end_date_picker').val('');
            $('.end_date_picker').datepicker({
                format: "yyyy-mm-d",
                autoclose: true,
                startDate: value
            });
        });


    })
    function SaveCoupon(form) {
        var options = {
            target: '',
            url: $(form).attr('action'),
            type: 'POST',
            success: function(res) {
                var count = res.count;
                var result = res.result;
                var body = $(".data-container");
                body.children("tr").remove();
                if (count == null) {
                    $(".intro").hide();
                    $(".countPro").hide();
                    $(".products").show();
                    body.children("tr").remove();
                    for (var i = 0; i < result.length; i++)
                        body.append("<tr><td>" + result[i]["storename"] + "</td><td>" + result[i]["total"] + "</td></tr>");
                } else {
                    $(".intro").hide();
                    $(".countPro").hide();
                    $(".products").show();
                    body.append("<tr><td>" + res.store["storename"] + "</td><td>" + count + "</td></tr>");
                }
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