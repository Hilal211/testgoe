<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span><i class="fa fa-user"></i></span> {{trans('keywords.Personal Details')}}
        </div>
        <div class="panel-body">
            @if(count($errors->get('personal'))>0)
            @include('errors.error')
            @endif
            {!! Form::open(["url"=>route('front.profile',[App::getLocale(),Route::current()->getParameter('id'),'personal',$type]),"method"=>"POST"]) !!}
            <div class="">
                <div class="row">
                    <div class="col-md-6 col-xs-12 form-group">
                        @include('common.required_mark') {{ Form::label('firstname',trans('keywords.First Name')) }}
                        {{ Form::text('firstname',@$user_details->firstname,['class'=>'form-control']) }}
                    </div>
                    <div class="col-md-6 col-xs-12 form-group">                    
                        @include('common.required_mark') {{ Form::label('lastname',trans('keywords.Last Name')) }}
                        {{ Form::text('lastname',@$user_details->lastname,['class'=>'form-control']) }}
                    </div>
                </div>
            </div>
            <div class="">
                <div class="row">
                    <div class="col-md-6 col-xs-12 form-group">
                        {{ Form::label('password',trans('keywords.Password')) }}
                        {{ Form::password('password',['class'=>'form-control']) }}
                        <span class="help-block">{{trans("keywords.Leave password fields blank if you don't want to change password")}}</span>
                    </div>
                    <div class="col-md-6 col-xs-12 form-group">           
                        {{ Form::label('password_confirmation',trans('keywords.Confirm Password')) }}
                        {{ Form::password('password_confirmation',['class'=>'form-control']) }}
                    </div>
                </div>
            </div>
            @if($type!='admin')
                <div class="row">
                    <div class="col-md-6 m-top-15 form-group">
                        <div class="form-group checkbox">
                            <label>
                                {{ Form::checkbox('subscribe','1',@$cust_details->is_subscribed,["id"=>'subscribe',"class"=>'i-check']) }} {{trans('keywords.Join our mailing list and receive great deals near you!')}}
                            </label>
                        </div>
                    </div>
                    <div id="zip_holder" class="m-top-minus-4 col-md-6" {!! (@$cust_details->is_subscribed == '1' || old('subscribe') == '1' ? : 'style="display: none"' ) !!}>
                        <div class="form-group">
                            @include('common.required_mark') {!! Form::label('password',trans("keywords.Postal / Zip Code"),['class'=>'control-label']) !!}
                            <input type="text" name="zip" class="form-control" placeholder="{{trans("keywords.Postal / Zip Code")}}" value="{{@$cust_details->zip}}">
                        </div>
                    </div>
                </div>
            @endif
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 text-right">
                        {{ Form::button(trans('keywords.Save'),['type'=>"submit",'class'=>'btn btn-primary btn-submit-fix']) }}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@for($i=0;$i<=2;$i++)
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span><i class="fa fa-map-marker"></i></span> {{trans('keywords.Shipping Details')}} {{$i+1}}
        </div>
        <div class="panel-body">
            @if(count($errors->get('shipping'))>0 && count($errors->get($i+1))>0)
                @include('errors.error')
            @endif
            {!! Form::open(["url"=>route('front.profile',[App::getLocale(),Route::current()->getParameter('id'),'shipping',$type]),"method"=>"POST",'id'=>$i+1]) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        @include('common.required_mark') {{ Form::label('shipping_phone',trans('keywords.Phone Number')) }}
                        {!! Form::text('shipping_phone',@$shipping_details[$i]->shipping_phone,['class'=>'form-control',"data-inputmask"=>'"mask": "(999) 999-9999"',"data-mask"=>""]) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        @include('common.required_mark') {{ Form::label('shipping_email',trans('keywords.Email')) }}
                        {!! Form::text('shipping_email',@$shipping_details[$i]->shipping_email,['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        @include('common.required_mark') {{ Form::label('shipping_email',trans('keywords.Address')) }}
                        {!! Form::text('shipping_address',@$shipping_details[$i]->shipping_address,['class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::label('shipping_apt',trans('keywords.Apt #')) }}
                        {!! Form::text('shipping_apt',@$shipping_details[$i]->shipping_apt,['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        @include('common.required_mark') {!! Form::label('state',trans('keywords.Province / State'),['class'=>'control-label']) !!}
                        {{ Form::select('shipping_state',$states,@$shipping_details[$i]->shipping_state,['class'=>'form-control select2','onchange'=>'getCities(this)']) }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        @include('common.required_mark') {!! Form::label('city',trans('keywords.City'),['class'=>'control-label']) !!}
                        {{ Form::select('shipping_city',$cities,@$shipping_details[$i]->shipping_city,['class'=>'form-control select2']) }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        @include('common.required_mark') {{ Form::label('shipping_city',trans('keywords.Postal / Zip Code')) }}
                        {!! Form::text('shipping_zip',@$shipping_details[$i]->shipping_zip,['class'=>'form-control']) !!}
                        {!! Form::hidden('id',(@$shipping_details[$i]->id!='' ? @$shipping_details[$i]->id : 0)) !!}
                        {!! Form::hidden('counter',$i+1) !!}
                    </div>
                </div>
            </div>
            {{-- <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                @include('common.required_mark') {{ Form::label('shipping_phone','Phone Number') }}
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('shipping_phone',@$shipping_details[$i]->shipping_phone,['class'=>'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                @include('common.required_mark') {{ Form::label('shipping_email','Email Address') }}
                            </div>
                            <div class="col-md-12">
                                {!! Form::text('shipping_email',@$shipping_details[$i]->shipping_email,['class'=>'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        @include('common.required_mark') {{ Form::label('shipping_email','Address') }}
                        {!! Form::text('shipping_address',@$shipping_details[$i]->shipping_address,['class'=>'form-control']) !!}
                    </div>
                   
                    <div class="col-md-3">
                        <div class="form-group">
                            @include('common.required_mark') {!! Form::label('state','Procince / State',['class'=>'control-label']) !!}
                            {{ Form::select('shipping_state',$states,@$shipping_details[$i]->shipping_state,['class'=>'form-control select2','onchange'=>'getCities(this)']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            @include('common.required_mark') {!! Form::label('city','City',['class'=>'control-label']) !!}
                            {{ Form::select('shipping_city',$cities,@$shipping_details[$i]->shipping_city,['class'=>'form-control select2']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        @include('common.required_mark') {{ Form::label('shipping_city','Postal / Zip Code') }}
                        {!! Form::text('shipping_zip',@$shipping_details[$i]->shipping_zip,['class'=>'form-control']) !!}
                        {!! Form::hidden('id',(@$shipping_details[$i]->id!='' ? @$shipping_details[$i]->id : 0)) !!}
                        {!! Form::hidden('counter',$i+1) !!}
                    </div>
                </div>
            </div> --}}
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 text-right">
                        {{ Form::button(trans('keywords.Save'),['type'=>"submit",'class'=>'btn btn-primary btn-submit-fix']) }}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endfor