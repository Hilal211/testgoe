<div id="ProductPriceModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{$StoreDetails->storename}} {{trans('keywords.Products Price')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4 invoice-col">
                        <b>{{trans('keywords.Store Address')}}</b>
                        <address>
                            <strong>{{$StoreDetails->storename}}</strong><br>
                            {!! $address !!}<br>
                            @if($StoreDetails->is_virtual == 0)
                                {{trans('keywords.Phone: ')}}{{$StoreDetails->contactnumber}}<br>
                            @endif
                        </address>
                    </div>
                    <div class="col-md-8 form-group">
                        <b>{{trans('keywords.Available Products')}}</b>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{trans('keywords.Name')}}</th>
                                    <th>{{trans('keywords.Qty In Stock')}}</th>
                                    <th>{{trans('keywords.Price')}}</th>
                                    <th>{{trans('keywords.Total')}}</th>
                                </tr>
                            </thead>
                            @foreach($products as $product)
                            <tr>
                                <td>{{$product->product_name}}</td>
                                @if($product->qty <= $product->inventory)
                                    <td>{{$product->qty}}</td>
                                @else
                                    <td>{{$product->inventory}} / {{$product->qty}}</td>
                                @endif
                                <td>{{Functions::GetPrice($product->price)}}</td>
                                @if($product->qty < $product->inventory)
                                    <td>{{Functions::GetPrice($product->qty*$product->price)}}</td>
                                @else
                                    <td>{{Functions::GetPrice($product->inventory*$product->price)}}</td>
                                @endif
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <b>{{trans('keywords.Alternatives for unavailable products')}}</b>
                        <table class="table table-bordered table-center">
                            <thead>
                                <tr>
                                    <th>{{trans('keywords.Name')}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($not_available_products as $item)
                                    <tr>
                                        <td>{{$item->product->product_name}}</td>
                                        <td><button class="btn btn-xs btn-primary" onclick="showRelated('{{$StoreDetails->id}}','{{$item->product_id}}')">Related Products</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <b>&nbsp;</b>
                        <table id="related_products_table" class="table table-bordered table-center" style="display: none">
                            <thead>
                                <tr>
                                    <th class="col-md-4">{{trans('keywords.Name')}}</th>
                                    <th class="col-md-1">{{trans('keywords.Price')}}</th>
                                    <th class="col-md-1"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    @if($store_class=='table-shopping-red' || $store_class=='table-shopping-orange')
                        <div class="col-md-12">
                            <span class="text-danger">
                                {{trans('keywords.*Please note that this store does not have sufficient Qty in stock for some of your cart items. You can proceed with this store with available Qty or choose another store.')}}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>