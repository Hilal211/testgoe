<form id="frmCheckout" name="frmCheckout" method="post" action="">
    <div class="row">
        <div class="col-md-12">
            <div class="cart-steps">
                <div class="col-md-4 nopadding">
                    <div class="box-shopping bg-green">
                        <h5 class="nomargin">1 <i class="fa fa-chevron-right pull-right"></i><span>{{trans('keywords.PICK YOUR ITEMS')}}</span></h5>
                    </div>
                </div>
                <div class="col-md-4 nopadding">
                    <div class="box-shopping bg-green">
                        <h5 class="nomargin">2 <i class="fa fa-chevron-right pull-right"></i><span>{{trans('keywords.\Select store')}}</span></h5>
                    </div>
                </div>
                <div class="col-md-4 nopadding">
                    <div class="box-shopping bg-green">
                        <h5 class="nomargin">3 <span>{{trans('keywords.Pay')}}</span></h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="gap gap-small gap-bottom"></div>
    </div>
    <div>
    <ul>
        <li>All perishable items can’t be returned or refunded.</li>
        <li>Minimum amount of purchase is $ 50 CAD.</li>
    </ul>
</div>
    <div class="row">   
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span><i class="fa fa-map-marker"></i></span> {{trans('keywords.Shipping Details')}} 
                </div>
                <div class="panel-body">
                    <div class="form-group orange-borerded-group">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                {{ Form::label('preferred_date',trans('keywords.Preferred Delivery Date')) }}
                                @if($storeDetails->is_virtual == 0)
                                    {{ Form::text('preferred_date','',['class'=>'form-control','is_virtual'=>'no','data-daysOfWeekDisabled'=>'']) }}
                                @else
                                    {{ Form::text('preferred_date','',['class'=>'form-control','is_virtual'=>'yes','data-daysOfWeekDisabled'=>'0,2,4']) }}
                                @endif
                            </div>
                            <div class="col-md-6 col-xs-12 display-none">
                                {{ Form::label('preferred_time',trans('keywords.Delivery Time')) }}
                                {{ Form::select('preferred_time',$slots,'',['class'=>'form-control select2','data-placeholder'=>'Select Time']) }}
                            </div>
                            <div class="col-md-12">
                                <span class="help-block pull-left">{{trans("keywords.We strive to deliver on your preferred time but it's not guaranteed, actual delivery time may vary.")}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                {{ Form::label('shipping_firstname',trans('keywords.First Name')) }}
                                {{ Form::text('shipping_firstname',@$user_details->firstname,['class'=>'form-control']) }}
                            </div>
                            <div class="col-md-6 col-xs-12">                    
                                {{ Form::label('shipping_lastname',trans('keywords.Last Name')) }}
                                {{ Form::text('shipping_lastname',@$user_details->lastname,['class'=>'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="btn-group form-group">
                        @if(count($shipping_details)>0)
                            <div class="row">
                                <div class="col-md-12">
                                    <span class="help-block no-margin">
                                        {{trans('keywords.Addresses saved in your profile are listed below. Please select one or you can type in your address.')}}
                                    </span>
                                </div>
                            </div>
                            @for($i=0;$i<count($shipping_details);$i++)
                                <label class="radio-inline col-md-12 shippingaddress">
                                    {!! Form::radio('shipping_address',$shipping_details[$i]->id,false,['class'=>'i-check addresses']) !!} 
                                    <span id="phn" class="display-none">{{$shipping_details[$i]->shipping_phone}}</span>
                                    <span id="email" class="display-none">{{$shipping_details[$i]->shipping_email}}</span>
                                    <span id="apt">{{$shipping_details[$i]->shipping_apt}}</span>, <span id="add">{{$shipping_details[$i]->shipping_address}}</span>
                                    <span id="city" data-id="{{$shipping_details[$i]->shipping_city}}">{{$shipping_details[$i]->city}}</span> , 
                                    <span id="state" data-id="{{$shipping_details[$i]->shipping_state}}">{{$shipping_details[$i]->state}}</span>
                                    <span id="zip">{{$shipping_details[$i]->shipping_zip}}</span>
                                </label>
                            @endfor
                        @endif
                    </div>
                    <div class="address-error form-group display-none">
                        <div class="alert alert-danger"></div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        {{ Form::label('shipping_phone',trans('keywords.Phone Number')) }}
                                    </div>
                                    <div class="col-md-12">
                                        {{ Form::text('shipping_phone',@$shipping_details[0]->shipping_phone,['class'=>'form-control',"data-inputmask"=>'"mask": "(999) 999-9999"',"data-mask"=>""]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        {{ Form::label('shipping_email',trans('keywords.Email')) }}
                                    </div>
                                    <div class="col-md-12">
                                        {{ Form::text('shipping_email',@$shipping_details[0]->shipping_email,['class'=>'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10">
                                {{ Form::label('shipping_address',trans('keywords.Address')) }}
                                {{ Form::text('shipping_address','',['class'=>'form-control']) }}
                            </div>
                            <div class="col-md-2">
                                {{ Form::label('shipping_apt',trans('keywords.Apt #')) }}
                                {{ Form::text('shipping_apt','',['class'=>'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('state',trans('keywords.Province / State'),['class'=>'control-label']) !!}
                                            {{ Form::select('shipping_state',$states,'',['id'=>'shipping_state','class'=>'form-control select2','onchange'=>'getCities(this)']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('city',trans('keywords.City'),['class'=>'control-label']) !!}
                                            {{ Form::select('shipping_city',$cities,'',['id'=>'shipping_city','class'=>'form-control select2']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {{ Form::label('shipping_zip',trans('keywords.Postal / Zip Code')) }}
                                            </div>
                                            <div class="col-md-12">
                                                {{ Form::text('shipping_zip','',['class'=>'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {{-- <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="Have a promocode? Enter here" onkeypress="couponKeyPress(event)"> --}}
                         {!! Form::label('city',trans('keywords.Coupon'),['class'=>'control-label']) !!}
                         {{ Form::text('coupon_code','',['class'=>'form-control','id'=>'coupon_code','placeholder'=>trans('keywords.Have a promocode? Enter here'),'onkeypress'=>'couponKeyPress(event)']) }}
                        <span id="no_coupon_msg" style="display: none" class="help-block text-danger"></span>
                        <div class="col-md-2">
                            <button id="applyCouponBtn" class="hide btn btn-primary" type="button" onclick="ApplyCoupon('{{Route::current()->getParameter('sid')}}')">
                                Apply!
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('shipping_notedelivery ',trans('keywords.Note'),['class'=>'control-label']) }}
                        {{ Form::text('shipping_notedelivery','',['class'=>'form-control','id'=>'notedelivery','placeholder'=>trans('keywords.Add your note for delivery'),'onkeypress'=>'couponKeyPress(event)']) }}
                    </div>
                    <div class="form-group ">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="button" id="add-reset-btn" class="btn btn-default btn-submit-fix display-none" onclick="ResetAddress()">{{trans('keywords.Reset')}}</button>

                                <button type="button" id="add-okay-btn" class="btn btn-primary btn-submit-fix" onclick="SaveShippingAddress('{{Route::current()->getParameter('sid')}}')">{{trans('keywords.Okay')}}</button>
                            </div>
                        </div>
                    </div>
                    <div id="reset-instruction" class="form-group display-none text-right">
                        <span class="help-block">{{trans('keywords.If you wish to change your address / coupon code again, please click "Reset" button.')}}</span>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span><i class="fa fa-shopping-cart"></i></span> {{trans('keywords.Order Details')}}
                </div>
                <div id="shipping-details" class="panel-body display-none">
                    <div class="col-md-4">
                        <div id="store_details_section" class="invoice-col p-btm-5">
                            <b>{{trans('keywords.Ship From')}}</b>
                            <address class="m-top-5">
                                <strong id="store_name"></strong><br>
                                <span id="address"></span><br>
                                Phone: <span id="phone"></span><br>
                            </address>
                        </div>
                        <div id="customer_details_section" class="invoice-col">
                            <b>{{trans('keywords.Ship To')}}</b>
                            <address class="m-top-5">
                                <strong>{{@$user_details->firstname.' '.@$user_details->lastname}}</strong><br>
                                <span id="customer_apt"></span>
                                <span id="customer_address1"></span><br>
                                <span id="customer_address2"></span><br>
                            </address>
                        </div>
                    </div>
                    <div class="col-md-8 p-btm-5">
                        <div class="table-responsive">
                            <table class="table table table-shopping-cart table-checkout-order font-12">
                                <thead>
                                    <tr>
                                        <th>{{trans('keywords.Product')}}</th>
                                        <th>{{trans('keywords.Title')}}</th>
                                        <th class="text-right">{{trans('keywords.Price')}}</th>
                                        <th>{{trans('keywords.Qty')}}</th>
                                        <th class="text-right">{{trans('keywords.Total')}}</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-store-list">
                                </tbody>
                                {{ Form::hidden('sub_total') }}
                                {{ Form::hidden('discount') }}
                                {{ Form::hidden('coupon_applied') }}
                                {{ Form::hidden('shipping_charge') }}
                                {{ Form::hidden('grand_total') }}
                                {{ Form::hidden('tax_total') }}
                                {{ Form::hidden('ftax_total') }}
                                {{ Form::hidden('ptax_total') }}
                                {{ Form::hidden('recycle_fee') }}
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <a  href="{{url('/')}}?refer=store-selection">{{trans('keywords.Edit Order')}}</a>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span><i class="fa fa-lock"></i></span> {{trans('keywords.Payment Details')}}
                </div>
                <div id="payment-details" class="panel-body display-none">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">{{trans('keywords.Card Type')}}:</div>
                            <div class="col-md-12">
                                <select id="CreditCardType" name="CreditCardType" class="form-control">
                                    <option value="5">Visa</option>
                                    <option value="6">MasterCard</option>
                                    <option value="7">American Express</option>
                                    <option value="8">Discover</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                {{trans('keywords.Credit Card Number')}}:
                                <input type="text" class="form-control" name="card_number" value="" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{trans('keywords.Card CVV')}}:
                                <input type="password" class="form-control" name="card_cvc" value="" />
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class="col-md-12">{{trans('keywords.Expiration Date')}}</div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select class="form-control" name="card_expiration_month">
                                    <option value="">{{trans('keywords.Month')}}</option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select class="form-control" name="card_expriration_year">
                                    <option value="">{{trans('keywords.Year')}}</option>
                                    @for($i = date("Y"); $i < date("Y")+15; $i++){
                                    <option value={{$i}}>
                                        {{$i}}
                                    </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <span>{{trans('keywords.Pay secure using your credit card.')}}</span>
                            </div>
                            <div class="col-md-12">
                                {{ Html::image(config('theme.ASSETS').config('theme.FRONT_IMG').'credit-card-logo.gif',"",['height'=>'30']) }}
                                {{ Html::image(config('theme.ASSETS').config('theme.FRONT_IMG').'pci_image.jpg',"",['height'=>'60']) }}
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-md-12">

                                <div class="clearfix"></div>
                            </div>
                            <div class="col-md-12">
                                <span>{{trans("keywords.We're NOT storing your card details as per PCI compliance.")}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 pull-left">
                    <div class="form-group">
                        <a class="btn btn-primary" href="{{url('/')}}?refer=store-selection">
                            << {{trans('keywords.PICK YOUR ITEMS')}}
                        </a>
                        <a class="btn btn-primary text-uppercase" href="{{URL::previous()}}">
                            < {{trans('keywords.\Select store')}}
                        </a>
                    </div>
                </div>
                <div class="col-md-5 text-right pull-left">
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" id="order_now_btn" disabled="disabled" class="btn btn-danger btn-submit-fix">
                            {{trans('keywords.ORDER NOW')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>