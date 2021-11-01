<div id="InvoiceModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{trans('keywords.Order #')}}{{$order->order_number}}
                    @if($route_name=='orders')
                    <a href="javascript:;" class="show-tooltip copy_link" data-placement="bottom" data-id='{{$order->id}}' onclick='CopyProducts(this,"repeat")' title="Buy Now"><i class="fa fa-refresh"></i></a>
                    <a href="javascript:;" class="show-tooltip copy_link" data-placement="bottom" data-id='{{$order->id}}' onclick='CopyProducts(this,"copy")' title="Add to Cart"><i class="fa fa-cart-plus"></i></a>
                    @endif
                    @if($order->status!='12')
                    @if(Auth::user()->hasRole('store_owner') || Auth::user()->hasRole('admin'))
                    <a href="javascript:;" class="show-tooltip" data-placement="bottom" data-id='{{$order->id}}' onclick='Refund(this)' title="Refund"><i class="fa fa-reply"></i> Refund</a>
                    @endif
                    @else
                    <a href="javascript:;" class="show-tooltip text-danger" data-placement="bottom"> Refunded</a>
                    @endif
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <p>{{trans('keywords.Status')}} : <span class="{{$obj->Status[$order->status]['class']}}">{{trans('keywords.'.$obj->Status[$order->status]['title'])}}</span></p>
                            </div>
                            <br>
                            <div class="col-md-12">
                                @if($order->preferred_date->format('Y-m-d')!='-0001-11-30')
                                <p>{{trans('keywords.Preferred Delivery Date')}} :
                                    {{$order->preferred_date->format('d M Y')}}
                                    {{$obj->AllDaySlots[$order->preferred_time]}}
                                </p>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <p>{{trans('keywords.Order Date')}} : {{$order->created_at->format('d M Y')}}</p>
                            </div>
                            <div class="col-sm-6 invoice-col p-btm-5">
                                <b>{{trans('keywords.Ship From')}}</b>
                                <address class="m-top-5">
                                    <strong>{{$storeDetails->storename}}</strong><br>
                                    {!! $address !!}<br>
                                    {{trans('keywords.Phone: ')}}{{$storeDetails->contactnumber}}<br>
                                </address>
                            </div>
                            <div class="col-sm-6 invoice-col">
                                <b>{{trans('keywords.Ship To')}}</b>
                                <address class="m-top-5">
                                    <strong>{{$order->shipping_firstname.' '.$order->shipping_lastname}}</strong><br>
                                    {{($order->shipping_apt!='' ? $order->shipping_apt.',' : "")}} {{$order->shipping_address}}<br>
                                    {{$order->cityname}} {{$order->statename}} {{$order->shipping_zip}}<br>
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <b>Note Delivery</b>
                                <hr />
                                <p style="word-wrap: break-word;">{{$order->shipping_note_delivery}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borerded table-shopping-cart">
                            <thead>
                                <tr>
                                    <th>{{trans('keywords.Item')}}</th>
                                    <th>{{trans('keywords.Qty / Unit')}}</th>
                                    <th>{{trans('keywords.Price')}}</th>
                                    <th>{{trans('keywords.Total')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->OrderProducts as $product)
                                <tr>
                                    <td>{{$product->product_name}}</td>
                                    <td>{{$product->product_qty}} {{$product->product_unit}}</td>
                                    <td>{{Functions::GetPrice($product->product_price)}}</td>
                                    <td>{{Functions::GetPrice($product->product_price*$product->product_qty)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:bold">{{trans('keywords.Sub Total')}}</td>
                                <td>{{Functions::GetPrice($order->sub_total)}}</td>
                            </tr>
                            @if($order->coupon_code)
                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:bold">
                                    <b>({{trans('keywords.Coupon')}} {{$order->coupon_code}} {{trans('keywords.is applied')}}) {{trans('keywords.Discount')}}:</b>
                                </td>
                                <td>
                                    -{{Functions::GetPrice($order->discount)}}
                                </td>
                            </tr>
                            @endif
                            @if($order->shipped_by=='1')
                            {{-- admin did delivery & user is store--}}
                            @if(Auth::user()->hasRole('store_owner'))
                            <tr class="hide">
                                <td colspan="3" style="text-align:right;font-weight:bold">{{trans('keywords.Shipping Charge')}}</td>
                                <td>{{Functions::GetPrice($order->shipping_charge)}}</td>
                            </tr>
                            @elseif((Auth::user()->hasRole('admin')) || (Auth::user()->hasRole('customer')))
                            {{-- user is admin or customer--}}
                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:bold">{{trans('keywords.Shipping Charge')}}</td>
                                <td>{{Functions::GetPrice($order->shipping_charge)}}</td>
                            </tr>
                            @endif

                            <tr>
                                <td rowspan="2" colspan="1" class="text-right">
                                    <b>{{$storeDetails->statename}} ({{$storeDetails->tax_description}}) {{trans('keywords.Tax')}}</b>
                                </td>
                                <td class="text-right" colspan="2"><b>{{trans('keywords.Federal')}} {{$storeDetails->ftax_percentage}}% </b></td>
                                <td>{{Functions::GetPrice($order->f_tax)}}</td>
                            </tr>
                            <tr>
                                <td class="text-right border-t-0" colspan="2"><b>{{trans('keywords.Province')}} {{$storeDetails->ptax_percentage}}%</td>
                                <td class="border-t-0">{{Functions::GetPrice($order->p_tax)}}</td>
                            </tr>
                            <tr class="display-none">
                                <td colspan="3" style="text-align:right;font-weight:bold">{{trans('keywords.Tax')}}</td>
                                <td>{{Functions::GetPrice($order->tax)}}</td>
                            </tr>

                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:bold">{{trans('keywords.Recycle Fee')}}</td>
                                <td>{{Functions::GetPrice($order->recycle_fee)}}</td>
                            </tr>

                            {{-- user is store--}}
                            @if(Auth::user()->hasRole('store_owner'))
                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:bold">{{trans('keywords.Grand Total')}}</td>
                                <td>{{Functions::GetPrice($order->sub_total+$order->tax+$order->recycle_fee)}}</td>
                            </tr>
                            @elseif((Auth::user()->hasRole('admin')) || (Auth::user()->hasRole('customer')))
                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:bold">{{trans('keywords.Grand Total')}}</td>
                                <td>{{Functions::GetPrice($order->grand_total)}}</td>
                            </tr>
                            @endif
                            @elseif($order->shipped_by=='2')
                            {{-- store did delivery --}}
                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:bold">{{trans('keywords.Shipping Charge')}}</td>
                                <td>{{Functions::GetPrice($order->shipping_charge)}}</td>
                            </tr>
                            <tr>
                                <td rowspan="2" colspan="1" class="text-right">
                                    <b>{{$storeDetails->statename}} ({{$storeDetails->tax_description}}) {{trans('keywords.Tax')}}</b>
                                </td>
                                <td class="text-right" colspan="2"><b>{{trans('keywords.Federal')}} {{$storeDetails->ftax_percentage}}% </b></td>
                                <td>{{Functions::GetPrice($order->f_tax)}}</td>
                            </tr>
                            <tr>
                                <td class="text-right border-t-0" colspan="2"><b>{{trans('keywords.Province')}} {{$storeDetails->ptax_percentage}}%</td>
                                <td class="border-t-0">{{Functions::GetPrice($order->p_tax)}}</td>
                            </tr>
                            <tr class="display-none">
                                <td colspan="3" style="text-align:right;font-weight:bold">{{trans('keywords.Tax')}}</td>
                                <td>{{Functions::GetPrice($order->tax)}}</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:bold">{{trans('keywords.Recycle Fee')}}</td>
                                <td>{{Functions::GetPrice($order->recycle_fee)}}</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:bold">{{trans('keywords.Grand Total')}}</td>
                                <td>{{Functions::GetPrice($order->grand_total)}}</td>
                            </tr>
                            @endif
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>