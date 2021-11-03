<div class="row">
    <div class="col-md-12">
        @include('errors.error')
    </div>
    <div class="col-md-12">
        <div class="alert alert-custom-warning" id="storemsg" style="display:none"></div>
    </div>
    <div class="col-md-12">
        <div class="cart-steps">
            <div class="col-md-4 nopadding">
                <div class="box-shopping bg-green">
                    <h5 class="nomargin">1 <i class="fa fa-chevron-right pull-right"></i><span>{{trans('keywords.Pick items')}}</span></h5>
                </div>
            </div>
            <div class="col-md-4 nopadding">
                <div class="box-shopping bg-green">
                    <h5 class="nomargin">2 <i class="fa fa-chevron-right pull-right"></i><span>{{trans('keywords.\Select store')}}</span></h5>
                </div>
            </div>
            <div class="col-md-4 nopadding">
                <div class="box-shopping ">
                    <h5 class="nomargin">3 {{trans('keywords.Pay')}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="gap gap-small gap-bottom"></div>
</div>
@if($zip)
<div class="alert alert-custom-success">
    <i class="fa fa-map-marker font-20 spin"></i>&nbsp;&nbsp;
    <span class="description">{{trans('keywords.Your current Postal / Zip Code is set to')}} <strong class='display_zip_code'>{{$zip}}</strong>. {{trans('keywords.following are the stores near to you. Click')}} <a href="javascript:;" class="zip-link" onclick="ResetCookie()">{{trans('keywords.here')}}</a> {{trans('keywords.to reset your Postal / Zip Code')}}</span>
    <input id="offset" name="offset" value="0" type="hidden">
</div>
@else
<div class="alert alert-custom-success">
    <i class="fa fa-map-marker font-20 spin"></i>&nbsp;&nbsp;
    {{trans('keywords.Your current Postal / Zip Code is set to')}} <strong class='display_zip_code'>Null</strong>, {{trans('keywords.Please Click')}} <a href="javascript:;" class="zip-link" onclick="ResetCookie()">{{trans('keywords.here')}}</a> {{trans('keywords.to set your Postal / Zip Code and proceed further')}}.
    <input id="offset" name="offset" value="0" type="hidden">
</div>
@endif
<div>
    <ul>
        <li>All perishable items can’t be returned or refunded.</li>
        <li>Minimum amount of purchase is $ 50 CAD.</li>
    </ul>
</div>
<div class="table-responsive">
    <table class="table table table-shopping-cart">
        <thead>
            <tr>
                <th>{{trans('keywords.Store Name')}}</th>
                <th>{{trans('keywords.Distance')}}</th>
                <th width='50'>{{trans('keywords.Price')}}</th>
                <th class="text-center">{{trans('keywords.Items in Stock')}}</th>
                <th width='50'>{{trans('keywords.Action')}}</th>
            </tr>
        </thead>
        <tbody class="tbody-store-list">
        </tbody>
    </table>
</div>
<div class="gap gap-small gap-bottom"></div>
<div id="loadmore" class="col-md-12 text-center" style="display:none">
    <a onclick="displayMoreRow()" href="javascript:;" class="">{{trans('keywords.Load more records')}}</a>
</div>
<div>
    <div class="col-md-12 no-padding">
        <h5 class="text-danger">{{trans('keywords.Press on the colored bubbles above to check the availability and choose alternative products')}}</h5>
    </div>
</div>
<div>
    <div class="col-md-4 no-padding store-product-info">
        <p class="table-shopping-success item-link pull-left" href="javascript:;"></p> <span class="store-info text-danger">{{trans('keywords.All items in stock')}}</span>
    </div>
    <div class="col-md-4 no-padding store-product-info">
        <p class="table-shopping-orange item-link pull-left" href="javascript:;"></p> <span class="store-info text-danger">{{trans('keywords.Quantity might be missing')}}</span>
    </div>
    <div class="col-md-4 no-padding store-product-info">
        <p class="table-shopping-red item-link pull-left" href="javascript:;"></p> <span class="store-info text-danger">{{trans('keywords.Items and quantity are missing')}}</span>
    </div>
</div>

<div class="gap gap-small gap-bottom"></div>
<a class="btn btn-primary pull-left" href="{{url('/')}}?refer=store-selection">
    << {{trans('keywords.PICK YOUR ITEMS')}}</a>
