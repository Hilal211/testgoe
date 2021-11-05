@extends('frontend.layout.default')

@section('content')
<div class="">
    <div class="gap gap-small"></div>
    <div class=" bg-orange-rounded">
        <div id="form-container" class="col-md-12 no-padding bordercard">
            <div class="registration-bg form-group">
                <div class="col-md-10 col-md-push-1">
                    <h1 class="widget-title text-center">{{trans('keywords.Store Registration')}}</h1>
                    @if(Session::get('success')=='')
                    <p class="description text-center">{{trans("keywords.Let's set you to become a Goecolo provider. Please enter a few information below, you will receive an email that will guide you through the registration process, Welcome to our family.")}}</p>
                    @endif
                </div>
            </div>
            @if(Session::get('success')!='')
            <div class="success-wizard text-center form-group">
                <h4 class="text-center">
                    <i class="fa fa-thumbs-o-up fa-4x text-success"></i><br>
                    <span class="success-thankyou">{{trans("keywords.You're Good to Go. Start receiving orders from hungry clients around you.")}}</span><br>
                    <hr>
                </h4>
                <h4 class="col-md-12">
                    {{trans("keywords.Thank you for registering with Goecolo! You're just one step away.")}}
                    <br><br>
                    {{trans('keywords.You will receive a verification link in your email within 24 to 48 hours, please follow the link steps.')}}
                    <br><br>
                </h4>
                <a href="{{url('/')}}" class="btn btn-primary"><i class="fa fa-mail-reply-all"></i> {{trans('keywords.Take me to the website.')}}</a>
            </div>
            @else
            <div class="col-md-8 col-md-push-2">
                <div class="stepwizard">
                    <div class="stepwizard-row setup-panel">
                        <div class="stepwizard-step">
                            <a href="#step-1" type="button" class="btn btn-primary-wz btn-circle">1</a>
                            <p>{{trans('keywords.Your Details')}}</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-2" type="button" class="btn btn-default-wz btn-circle" disabled="disabled">2</a>
                            <p>{{trans('keywords.Store Details')}}</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-3" type="button" class="btn btn-default-wz btn-circle" disabled="disabled">3</a>
                            <p>{{trans('keywords.Legal Details')}}</p>
                        </div>
                    </div>
                </div>
                @if(count($errors->get('all'))>0)
                @include('errors.error')
                <span id="isError"></span>
                @endif
                @if(count($errors->get('address'))>0)
                @include('errors.error')
                <span id="invalidAddress"></span>
                @endif
                <div class="alert alert-danger form-errors display-none">
                    <ul>

                    </ul>
                </div>
                {!! Form::open(["id"=>"frmStoreOwner","name"=>"frmStoreOwner","url"=>App::getLocale()."/store_owner/all","method"=>"POST"]) !!}
                <div class=" setup-content" id="step-1">
                    <div class="col-xs-12">
                        <div class="">
                            <h3>&nbsp;</h3>
                            <div class="form-group">
                                @include('common.required_mark') {!! Form::label('username',trans('keywords.Store Contact Person Name'),['class'=>'control-label']) !!}
                                {!! Form::text('username','',['class'=>'form-control','placeholder'=>'Enter Name']) !!}
                            </div>
                            <div class="form-group">
                                @include('common.required_mark') {!! Form::label('email',trans('keywords.Email'),['class'=>'control-label']) !!}
                                {!! Form::text('email','',['class'=>'form-control','placeholder'=>trans('keywords.Enter Email')]) !!}
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @include('common.required_mark') {!! Form::label('password',trans('keywords.Password'),['class'=>'control-label']) !!}
                                        {!! Form::password('password',['class'=>'form-control 123','placeholder'=>trans('keywords.Enter Password')]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('password_confirmation',trans('keywords.Confirm Password'),['class'=>'control-label']) !!}
                                        {!! Form::password('password_confirmation',['class'=>'form-control','placeholder'=>trans('keywords.Retype Password')]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                @include('common.required_mark') {!! Form::label('contact',trans('keywords.Contact #'),['class'=>'control-label']) !!}
                                {!! Form::text('contact','',['class'=>'form-control','placeholder'=>trans('keywords.Enter Contact #'),"data-inputmask"=>'"mask": "(999) 999-9999"',"data-mask"=>""]) !!}
                            </div>
                            {{ Form::button(trans('keywords.Next'),["type"=>"button","class"=>"btn btn-primary nextBtn pull-right"]) }}
                            @if(Session::get('success')=='')
                            <div class=" bg-orange-rounded" >
                                <div class="registration-bg registration-bg-footer col-md-12 text-center" style="margin-top:60px;">
                                    <div class="row">
                                        <div class="description col-xs-12 font-16">
                                            <p class="no-margin">{{trans('keywords.Already have an account? Login')}}
                                                <a class="login-link" href="{{route('login')}}" data-effect="mfp-move-from-top">{{trans('keywords.here')}}</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class=" setup-content" id="step-2">
                    <div class="col-xs-12">
                        <div class="">
                            <h3> &nbsp;</h3>
                            <div class="form-group">
                                @include('common.required_mark') {!! Form::label('store_name',trans('keywords.Store Name'),['class'=>'control-label']) !!}
                                {!! Form::text('store_name','',['class'=>'form-control','placeholder'=>trans('keywords.Enter Store Name')]) !!}
                            </div>
                            <div class="form-group">
                                @include('common.required_mark') {!! Form::label('cat',trans('keywords.Do you offer the Home Delivery service?'),['class'=>'control-label']) !!}
                                <div>
                                    <label class="radio-inline">
                                        {!! Form::radio('home_delivery','1',false,['class'=>'i-check']) !!} {{trans('keywords.Yes')}}
                                    </label>
                                    <label class="radio-inline">
                                        {!! Form::radio('home_delivery','0',false,['class'=>'i-check']) !!} {{trans('keywords.No')}}
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @include('common.required_mark') {!! Form::label('address_1',trans('keywords.Enter Address 1'),['class'=>'control-label']) !!}
                                        {!! Form::text('address_1','',['class'=>'form-control','placeholder'=>trans('keywords.Street Address')]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('address_2',trans('keywords.Enter Address 2'),['class'=>'control-label']) !!}
                                        {!! Form::text('address_2','',['class'=>'form-control','placeholder'=>trans('keywords.Apartment, Suite, Unit etc... (optional)')]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        @include('common.required_mark') {!! Form::label('state',trans('keywords.Province / State'),['class'=>'control-label']) !!}
                                        {{ Form::select('state',$states,'',['class'=>'form-control select2','onchange'=>'getCities(this)']) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        @include('common.required_mark') {!! Form::label('city',trans('keywords.City'),['class'=>'control-label']) !!}
                                        {{ Form::select('city',$cities,'',['class'=>'form-control select2','onchange'=>'CheckCity(this)']) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        @include('common.required_mark') {!! Form::label('zip',trans('keywords.Postal / Zip Code'),['class'=>'control-label']) !!}
                                        {{ Form::text('zip','',['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                @include('common.required_mark') {!! Form::label('storetype',trans('keywords.What type of store you have?'),['class'=>'control-label']) !!}
                                {{ Form::select('storetype',$types,'',['class'=>'form-control select2']) }}
                            </div>
                            {{ Form::button(trans('keywords.Prev'),["type"=>"button","class"=>"btn btn-primary prevBtn  pull-left"]) }}
                            {{ Form::button(trans('keywords.Next'),["type"=>"button","class"=>"btn btn-primary nextBtn  pull-right"]) }}
                        </div>
                    </div>
                </div>
                <div class=" setup-content" id="step-3">
                    <div class="col-xs-12">
                        <div class="">
                            <h3>&nbsp;</h3>
                            <div class="form-group">
                                @include('common.required_mark') {!! Form::label('legal_entity_name',trans('keywords.Legal Entity Name'),['class'=>'control-label']) !!}
                                {!! Form::text('legal_entity_name','',['class'=>'form-control','placeholder'=>trans('keywords.Enter Legal Entity Name')]) !!}
                            </div>
                            <div class="form-group">
                                @include('common.required_mark') {!! Form::label('year',trans('keywords.Year of Establishment'),['class'=>'control-label']) !!}
                                {!! Form::text('year','',['class'=>'form-control','placeholder'=>trans('keywords.Enter Year of Establishment')]) !!}
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('gst',trans('keywords.GST #'),['class'=>'control-label']) !!}
                                        {!! Form::text('gst','',['class'=>'form-control','placeholder'=>trans('keywords.Enter GST number')]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('hst',trans('keywords.HST #'),['class'=>'control-label']) !!}
                                        {!! Form::text('hst','',['class'=>'form-control','placeholder'=>trans('keywords.Enter HST number')]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! app('captcha')->display(); !!}
                            </div>
                            {{ Form::button(trans('keywords.Prev'),["type"=>"button","class"=>"btn btn-primary prevBtn  pull-left"]) }}
                            {{ Form::button(trans('keywords.Submit'),["type"=>"submit","class"=>"btn btn-primary pull-right"]) }}
                            {{-- Form::button('Next',["type"=>"button","class"=>"btn btn-primary nextBtn  pull-right"]) --}}
                        </div>
                    </div>
                </div>
                <div class="gap gap-small gap-bottom"></div>
                {!! Form::close() !!}
                @endif
                <div class="limited-access-holder  display-none">
                    <div class="col-md-12">
                        <p class="description">{{trans('keywords.Currently we are serving only Montreal area, click')}} <a href="#get_notification_modal" data-toggle="modal" class="description-link">{{trans('keywords.here')}}</a> {{trans('keywords.if you\'d like to get notification when we start serving your area.')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@include('common.notification_modal',['type'=>'store'])
@stop

@section('page_custom_js')
<script type="text/javascript">
    $(document).ready(function() {
        var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn'),
            allPrevBtn = $('.prevBtn');

        allWells.hide();

        navListItems.click(function(e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                $item = $(this);

            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-primary-wz').addClass('btn-default-wz');
                $item.addClass('btn-primary-wz');
                allWells.hide();
                $target.show();
                $target.find('input:eq(0)').focus();
            }
        });

        allNextBtn.click(function() {
            $(".form-errors").hide();
            $("#form-container").loader('show');
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                Form = $(this).parents('form'),
                nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a")
            if (curStepBtn == "step-3") {
                Data = Form.serialize();
            } else {
                Data = $(curStep).find('input,select,textarea').serialize();
            }

            $.ajax({
                type: "POST",
                url: "store_owner/" + curStepBtn,
                data: Data,
                dataType: "json",
                success: function(res) {
                    nextStepWizard.removeAttr('disabled').trigger('click');
                    $("#form-container").loader('hide');
                    /*console.log(res.token);
                    console.log($(Form).find('input[name="_token"]').val());
                    if(curStepBtn=="step-3" && res.status=='success'){
                        alert('yea');
                        $(Form).find('input[name="_token"]').val(res.token)
                        $(Form).submit();
                    }*/
                    /*if(res.status=='store_registered'){
                        $(".stepwizard").addClass("hide");
                        $(".setup-content").addClass("hide");
                        $(".success-wizard").removeClass("hide");
                        $(Form)[0].reset();
                        $(Form)[0].reset();
                        setTimeout(function(){                            
                            //window.location.reload();
                        },1000)
                    }*/
                },
                error: function(jqXHR, exception) {
                    $("#form-container").loader('hide');
                    var Response = jqXHR.responseText,
                        ErrorBlock = Form.prev('.form-errors'),
                        Response = $.parseJSON(Response);
                    DisplayErrorMessages(Response, ErrorBlock, 'ul');
                    grecaptcha.reset();
                }
            });
            // var curStep = $(this).closest(".setup-content"),
            // curStepBtn = curStep.attr("id"),
            // nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            // curInputs = curStep.find("input[type='text'],input[type='email'],input[type='tel']"),
            // isValid = true;

            // $(".form-group").removeClass("has-error");
            // for (var i = 0; i < curInputs.length; i++) {
            //     if (!curInputs[i].validity.valid) {
            //         isValid = false;
            //         $(curInputs[i]).closest(".form-group").addClass("has-error");
            //     }
            // }

            // if (isValid)
            //     nextStepWizard.removeAttr('disabled').trigger('click');
        });

        allPrevBtn.click(function() {
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                prevStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a");

            $(".form-group").removeClass("has-error");
            prevStepWizard.removeAttr('disabled').trigger('click');
        });
        if ($('#isError').length > 0) {
            $('a[href="#step-3"]').prop('disabled', false);
            $('a[href="#step-2"]').removeAttr('disabled');
            $('a[href="#step-3"]').click();
        } else {
            $('div.setup-panel div a.btn-primary-wz').trigger('click');
        }
        if ($('#invalidAddress').length > 0) {

            $('a[href="#step-2"]').removeAttr('disabled');
            $('a[href="#step-2"]').click();
        } else {
            $('div.setup-panel div a.btn-primary-wz').trigger('click');
        }

    });
    // function submitWizard(){
    //     $(".stepwizard").addClass("hide");
    //     $(".setup-content").addClass("hide");
    //     $(".success-wizard").removeClass("hide");
    // }
</script>
<script type="text/javascript">
    var Limitedmsg = '{{trans("keywords.Currently we are serving only Montreal area, Please insert below details, We'
    ll inform you when we provide services in your city.
    ")}}';
    var Successmsg = '{{trans("keywords.Details saved, we'
    ll inform you when we provide services in your city.
    ")}}';
</script>
@stop