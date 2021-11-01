@extends('frontend.layout.default')

@section('content')
<div class="container">
	<div class="gap gap-small"></div>
	<div class="row bg-white-rounded">
		<div class="col-md-8 col-md-push-2">
			<h1 class="widget-title text-center">{{trans('keywords.Order #')}}{{$order->order_number}}</h1>
            <a href="{{route("pages.order",\Crypt::encrypt(Auth::user()->id))}}" class="col-md-12 text-center">{{trans('keywords.Go to My Orders')}}</a>
			<div class="gap gap-small gap-border"></div>
        </div>
        <div class="col-md-6">
            <div class="col-md-12">
                <p>{{trans('keywords.Status : ')}}<span class="{{$obj->Status[$order->status]['class']}}">{{$obj->Status[$order->status]['title']}}</span></p>
            </div>
            <div class="col-md-6">
                <p>{{trans('keywords.Order Date : ')}}{{$order->created_at->format('d M Y')}}</p>
            </div>
            <div class="col-md-6">
                <p>{{trans('keywords.Delivery Date : 20 Jun 2016')}}</p>
            </div>
            <div class="col-sm-6 invoice-col">
              <b>{{trans('keywords.SHIPPED FROM')}}</b>
                <address>
                    <strong>{{$storeDetails->storename}}</strong><br>
                    {{$storeDetails->add1}}<br>
                    {{$storeDetails->add2}}<br>
                    {{$storeDetails->CityName}} , {{$storeDetails->StateName}} {{$storeDetails->zip}}<br>
                    {{trans('keywords.Phone: ')}}{{$storeDetails->contactnumber}}<br>
                </address>
            </div>
            <div class="col-sm-6 invoice-col">
              <b>{{trans('keywords.SHIPPED TO')}}</b>
              <address>
                    {{$order->shipping_address}}<br>
                    {{$order->shipping_city}} {{$order->shipping_state}} {{$order->shipping_zip}}<br>
                </address>
            </div>
        </div>
        <div class="col-md-6">
            <table class="table table-borerded">
                <thead>
                    <tr>
                        <th>{{trans('keywords.Item')}}</th>
                        <th>{{trans('keywords.Qty / Unit')}}</th>
                        <th>{{trans('keywords.Price')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->OrderProducts as $product)
                    <tr>
                        <td>{{$product->product_name}}</td>
                        <td>{{$product->product_qty}} {{$product->product_unit}}</td>
                        <td>{{Functions::GetPrice($product->product_price*$product->product_qty)}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tr>
                    <td></td>
                    <td><b>{{trans('keywords.Shipping Charge')}}</b></td>
                    <td>{{Functions::GetPrice($order->shipping_charge)}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td><b>{{trans('keywords.grand total')}}</b></td>
                    <td>{{Functions::GetPrice($order->grand_total)}}</td>
                </tr>
            </table>
        </div>
        
    </div>
</div>
@stop
@section('page_custom_js')

@stop