<html>
<body>
    <div style="width:600px; border:15px #88c000 solid;margin:0 auto;font-family: sans-serif; color: #000AAA; display: table;">
        <table border="0" cellspacing="0" style="font-size: 14px;float: left;width: 100%;">
            <tr style="background: #EEEEEE;">
                <td align="center" colspan="2" style="padding:10px">
                    <img src="https://www.goecolo.com/public/assets/frontend/img/logo-center-{{App::getLocale()}}.png">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666"><b>Hello,</b></td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666">
                    @if($order->shipped_by=='1')
                    You need to deliver this order.
                    @else
                    The store is handling delivery of this order. No action required from you.
                    @endif
                    <br><br>
                    Here is your order details:
                    <Br><br>
                        <table border='1' style="border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="padding: 7px;">Product</th>
                                    <th style="padding: 7px;">Qty</th>
                                    <th style="padding: 7px;">Price</th>
                                    <th style="padding: 7px;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $product)
                                <tr>
                                    <td style="padding: 5px;">{{$product->product_name}}</td>
                                    <td style="padding: 5px;">{{$product->qty}} {{$product->item_name}}</td>
                                    <td style="padding: 5px;">{{Functions::GetPrice($product->price)}}</td>
                                    <td style="padding: 5px;">{{Functions::GetPrice($product->qty*$product->price)}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td style="padding:5px;text-align:right;font-weight:bold" colspan="3">Sub Total</td>
                                    <td style="padding:5px">{{Functions::GetPrice($order->sub_total)}}</td>
                                </tr>
                                @if($order->coupon_code)
                                    <tr>
                                        <td style="padding:5px;text-align:right;font-weight:bold" colspan="3">Coupon {{$order->coupon_code}} is applied Discount</td>
                                        <td style="padding:5px">-{{Functions::GetPrice($order->discount)}}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding:5px;text-align:right;font-weight:bold" colspan="3">Shipping Charge</td>
                                    <td style="padding:5px">{{Functions::GetPrice($order->shipping_charge)}}</td>
                                </tr>

                                <tr>
                                    <td rowspan="2" colspan="1" class="text-right">
                                        <b>{{($store_details->is_virtual==0 ? $store_details->statename : $store_details->legelstatename)}} ({{$store_details->tax_description}}) Tax</b>
                                    </td>
                                    <td class="text-right" colspan="2"><b>Federal {{$store_details->ftax_percentage}}% </b></td>
                                    <td>{{Functions::GetPrice($order->f_tax)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right border-t-0" colspan="2"><b>Province {{$store_details->ptax_percentage}}%</td>
                                    <td class="border-t-0">{{Functions::GetPrice($order->p_tax)}}</td>
                                </tr>

                                <tr style="display: none">
                                    <td style="padding:5px;text-align:right;font-weight:bold" colspan="3">Tax</td>
                                    <td style="padding:5px">{{Functions::GetPrice($order->tax)}}</td>
                                </tr>

                                <tr>
                                    <td style="padding:5px;text-align:right;font-weight:bold" colspan="3">Recycle Fee</td>
                                    <td style="padding:5px">{{Functions::GetPrice($order->recycle_fee)}}</td>
                                </tr>
                                <tr>
                                    <td style="padding:5px;text-align:right;font-weight:bold" colspan="3" >Grand Total</td>
                                    <td style="padding: 5px;">{{Functions::GetPrice($order->grand_total)}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>
                        Customer Shipping Address <br/>
                        <b>{{$order->shipping_firstname}} {{$order->shipping_lastname}}</b> <br> {{($order->shipping_apt!='' ? $order->shipping_apt.',' : "")}} {{$order->shipping_address}}, <br> {{$order->CityName}}, {{$order->StateName}}, {{$order->shipping_zip}}
                        <br><br>
                        @if($order->preferred_date!='' && $order->preferred_time!='')
                        Scheduled Delivery Date: {{$order->preferred_date->format('d M Y')}} {{$order->preferred_time}}
                        <br><br>
                        @endif
                        <br><br>
                        Store Details <br/>
                        @if($store_details->is_virtual == 0)
                            <b>{{$store_details->storename}}</b> <br/> {{trim($store_details->add1.' '.$store_details->add2)}}, <br> {!! $storeAdd !!}
                        @else
                            <b>{{$store_details->legalentityname}}</b> <br> {!! $storeAdd !!}
                        @endif
                        <br><br>
                        @if($order->preferred_date!='0000-00-00' && $order->preferred_time!='')
                        Preferred Delivery Time: {{$order->preferred_date->format('d M Y')}} {{$order->preferred_time}}
                        <br><br>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:10px;color:#666666">Thanks,<br/>-Team Goecolo</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:10px;font-size: 12px;;color:#BDBDBD; background: #3C464F">
                        You're receiving this email because you signed up for Goecolo<br/>
                        Please do not reply to this email. Emails sent to this address will not be answered.<br/><br/>
                        Copyright © 2016 - {{Carbon::now()->format('Y')}} Goecolo. <br/>All rights reserved.
                    </td>
                </tr>
            </table>
        </div>
    </body>
    </html>