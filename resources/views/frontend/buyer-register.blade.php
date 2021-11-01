@extends('frontend.layout.default')

@section('content')
<div class="container">
    <div class="gap gap-small"></div>
    <div class="row bg-orange-rounded">
        <div class="col-md-12 no-padding">
            <div class="registration-bg form-group">
                <div class="col-md-12">
                    <h1 class="widget-title text-center">{{ trans('keywords.Shopper Registration') }}</h1>
                    <p class="description text-center">{{ trans('keywords.Let’s get you ready to start shopping with us. Please enter some info below. You will receive a confirmation message, please follow the steps in the email to complete your registration also note  your email will be your username.') }}</p>
                </div>
            </div>
            <div class="col-md-8 col-md-push-2">
                <div class="gap gap-small gap-border"></div>
                <div class="alert alert-success form-success display-none">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <h3>{{ trans('keywords.Thank You!') }}</h3> <span></span>
                </div>
                <div class="alert alert-danger form-errors display-none">
                    <ul>
                        
                    </ul>
                </div>
                {!! Form::open(["id"=>"frmBuyerRegister","name"=>"frmBuyerRegister","url"=>"buyer-register","method"=>"POST","autocomplete"=>"off"]) !!}
                <div class="row setup-content">
                    <div class="col-xs-12">
                        <div class="">
                            <div class="form-group">
                                @include('common.required_mark') {!! Form::label('email',trans("keywords.Email"),['class'=>'control-label']) !!}
                                {!! Form::text('email','',['class'=>'form-control','placeholder'=>trans("keywords.Enter Email")]) !!}
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @include('common.required_mark') {!! Form::label('state',trans("keywords.Province / State"),['class'=>'control-label']) !!}
                                        {{ Form::select('state',$states,'',['class'=>'form-control select2','onchange'=>'getCities(this)']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @include('common.required_mark') {!! Form::label('city',trans("keywords.City"),['class'=>'control-label']) !!}
                                        {{ Form::select('city',$cities,'',['class'=>'form-control select2']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                @include('common.required_mark') {!! Form::label('password',trans("keywords.Password"),['class'=>'control-label']) !!}
                                {!! Form::password('password',['class'=>'form-control 123','placeholder'=>trans("keywords.Enter Password")]) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('password_confirmation',trans("keywords.Confirm Password"),['class'=>'control-label']) !!}
                                {!! Form::password('password_confirmation',['class'=>'form-control','placeholder'=>trans("keywords.Retype Password")]) !!}
                            </div>
                            <div class="row">
                                <div class="col-md-7 m-top-15 form-group">
                                    <div class="form-group checkbox">
                                        <label>
                                            {{ Form::checkbox('subscribe','1','',["id"=>'subscribe',"class"=>'i-check']) }} {{trans('keywords.Join our mailing list and receive great deals near you!')}}
                                        </label>
                                    </div>
                                </div>
                                <div id="zip_holder" class="m-top-minus-4 col-md-5" {!! (old('subscribe') == 1) ? "" : 'style="display: none"' !!}>
                                    <div class="form-group">
                                        @include('common.required_mark') {!! Form::label('zip',trans("keywords.Postal / Zip Code"),['class'=>'control-label']) !!}
                                        <input type="text" name="zip" class="form-control" placeholder="{{trans("keywords.Postal / Zip Code")}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! app('captcha')->display(); !!}
                            </div>
                            <div class="col-md-12 form-group">
                                {{ Form::button(trans("keywords.Create Account"),["type"=>"submit","class"=>"btn btn-primary nextBtn pull-right"]) }}
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <p>Currently we are serving only Montreal area, click 
                                    <a href="#get_notification_modal" data-toggle="modal">here</a>
                                    if you'd like to get notification when we start serving your area.</p>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="gap gap-small gap-bottom"></div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @if(Session::get('success')=='')
    <div class="row bg-orange-rounded">
        <div class="registration-bg registration-bg-footer col-md-12 text-center">
            <div class="row">
                <div class="description col-xs-12 font-16">
                    <p class="no-margin">{{ trans("keywords.Already have an account? Login") }}
                    <a class="login-link" href="{{route('login')}}" data-effect="mfp-move-from-top"> {{ trans("keywords.here") }}</a></p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@stop
@section('page_custom_js')
<script>
    $(document).ready(function () {
        $('#subscribe').on('ifChanged', function () {
            $('#zip_holder').toggle();
        })
        $("#frmBuyerRegister").submit(function (event) {
            event.preventDefault();
            $("body").loader('show');
            $('.form-errors').hide();
            Form = $('#frmBuyerRegister');
            Data = Form.serialize();
            $.ajax({
                type: "POST",
                url: "buyer-register",
                data: Data,
                dataType: "json",
                success: function (res) {
                    $("body").loader('hide');
                    if (res.status == 'buyer_registered') {
                        var Response = res.message,
                        SuccessBlock = $('.form-success');
                        DisplaySuccessMessage(res.message,SuccessBlock)
                        $(Form).find('input').val('');
                        $(Form).find('input:checkbox').iCheck('uncheck');
                        $(Form).find('input:checkbox').iCheck('update');
                        $(Form).find('select[name="state"]').val('').change();
                        $(Form).find('select[name="city"]').val('').change();
                        grecaptcha.reset();
                    }
                },
                error: function (jqXHR, exception) {
                    $("body").loader('hide');
                    var Response = jqXHR.responseText,
                    ErrorBlock = $('.form-errors'),
                    Response = $.parseJSON(Response);
                    DisplayErrorMessages(Response, ErrorBlock, 'ul');
                    grecaptcha.reset();
                }
            });
        });
    });
</script>
@stop