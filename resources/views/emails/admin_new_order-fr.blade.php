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
                <td colspan="2" style="padding:10px;color:#666666"><b>Bonjour,</b></td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666">
                    @if($order->shipped_by=='1')
                    Vous devez livrer cet ordre.
                    @else
                    Le magasin s'occupe de la livraison de cette commande. Aucune action requise de votre part.
                    @endif
                    <br><br>
                    Voici les détails de votre commande:
                    <Br><br>
                        <table border='1' style="border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="padding: 7px;">Produit</th>
                                    <th style="padding: 7px;">Quantité</th>
                                    <th style="padding: 7px;">Prix</th>
                                    <th style="padding: 7px;">Total de la commande</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $product)
                                <tr>
                                    <td style="padding: 5px;">{{$product->fr_product_name}}</td>
                                    <td style="padding: 5px;">{{$product->qty}} {{$product->item_name}}</td>
                                    <td style="padding: 5px;">{{Functions::GetPrice($product->price)}}</td>
                                    <td style="padding: 5px;">{{Functions::GetPrice($product->qty*$product->price)}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td style="padding:5px;text-align:right;font-weight:bold" colspan="3">Sous-Total</td>
                                    <td style="padding:5px">{{Functions::GetPrice($order->sub_total)}}</td>
                                </tr>
                                @if($order->coupon_code)
                                    <tr>
                                        <td style="padding:5px;text-align:right;font-weight:bold" colspan="3">Le coupon {{$order->coupon_code}} est appliqué Rabais</td>
                                        <td style="padding:5px">-{{Functions::GetPrice($order->discount)}}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding:5px;text-align:right;font-weight:bold" colspan="3">Frais de port</td>
                                    <td style="padding:5px">{{Functions::GetPrice($order->shipping_charge)}}</td>
                                </tr>

                                <tr>
                                    <td rowspan="2" colspan="1" class="text-right">
                                        <b>{{($store_details->is_virtual==0 ? $store_details->statename : $store_details->legelstatename)}} ({{$store_details->tax_description}}) Impôt</b>
                                    </td>
                                    <td class="text-right" colspan="2"><b>Fédéral {{$store_details->ftax_percentage}}% </b></td>
                                    <td>{{Functions::GetPrice($order->f_tax)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right border-t-0" colspan="2"><b>Province {{$store_details->ptax_percentage}}%</td>
                                    <td class="border-t-0">{{Functions::GetPrice($order->p_tax)}}</td>
                                </tr>

                                <tr style="display: none">
                                    <td style="padding:5px;text-align:right;font-weight:bold" colspan="3">Impôt</td>
                                    <td style="padding:5px">{{Functions::GetPrice($order->tax)}}</td>
                                </tr>

                                <tr>
                                    <td style="padding:5px;text-align:right;font-weight:bold" colspan="3">Frais de recyclage</td>
                                    <td style="padding:5px">{{Functions::GetPrice($order->recycle_fee)}}</td>
                                </tr>
                                <tr>
                                    <td style="padding:5px;text-align:right;font-weight:bold" colspan="3" >somme finale</td>
                                    <td style="padding: 5px;">{{Functions::GetPrice($order->grand_total)}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>
                        Adresse de livraison du client <br/>
                        <b>{{$order->shipping_firstname}} {{$order->shipping_lastname}}</b> <br> {{($order->shipping_apt!='' ? $order->shipping_apt.',' : "")}} {{$order->shipping_address}}, <br> {{$order->CityName}}, {{$order->StateName}}, {{$order->shipping_zip}}
                        <br><br>
                        @if($order->preferred_date!='' && $order->preferred_time!='')
                        Délai de livraison préféré: {{$order->preferred_date->format('d M Y')}} {{$order->preferred_time}}
                        <br><br>
                        @endif
                        <br><br>
                        Détails sur le magasin <br/>
                        @if($store_details->is_virtual == 0)
                            <b>{{$store_details->storename}}</b> <br/> {{trim($store_details->add1.' '.$store_details->add2)}}, <br> {!! $storeAdd !!}
                        @else
                            <b>{{$store_details->legalentityname}}</b> <br/> {{trim($store_details->legal_add1.' '.$store_details->legal_add2)}}, <br> {!! $storeAdd !!}
                        @endif
                        <br><br>
                        @if($order->preferred_date!='0000-00-00' && $order->preferred_time!='')
                        Délai de livraison préféré: {{$order->preferred_date->format('d M Y')}} {{$order->preferred_time}}
                        <br><br>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:10px;color:#666666">Merci,<br/>-Équipe GoEcolo</td>
                </tr>
                <tr>
                <td colspan="2" style="padding:10px;font-size: 12px;;color:#BDBDBD; background: #3C464F">
                    Vous recevez ce courriel parce que vous vous êtes inscrit à Goecolo<br/>
                    S'il vous plait ne répondez pas à cet email. Les e-mails envoyés à cette adresse ne seront pas répondus.<br/><br/>
                    Droits d'auteur © 2016 - {{Carbon::now()->format('Y')}} Goecolo.<br/>Tous les droits sont réservés.
                </td>
            </tr>
            </table>
        </div>
    </body>
    </html>