<div id="full-shopping-cart" class="hide">
    <div class="row">
        <div class="col-md-12">
            <div class="cart-steps">
                <div class="col-md-4 nopadding">
                    <div class="box-shopping bg-green">
                        <h5 class="nomargin">1 <i class="fa fa-chevron-right pull-right"></i><span>{{trans('keywords.Pick items')}}</span></h5>
                    </div>
                </div>
                <div class="col-md-4 nopadding">
                    <div class="box-shopping">
                        <h5 class="nomargin">2 <i class="fa fa-chevron-right pull-right"></i><span>{{trans('keywords.\Select store')}}</span></h5>
                    </div>
                </div>
                <div class="col-md-4 nopadding">
                    <div class="box-shopping">
                        <h5 class="nomargin">3 <span>{{trans('keywords.Pay')}}</span></h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="gap gap-small gap-bottom"></div>
        {{-- <div class="col-md-12">
            <div class="alert alert-custom-success">
                <i class="fa fa-comments"></i> {{trans('keywords.If you would like to see a product added to our database, please send us a detailed note in the feedback section with a subject line : New product')}}
    </div>
</div> --}}
</div>
<div class="table-responsive" style="background: #fff">
    <table class="table table table-shopping-cart">
        <thead>
            <tr>
                <th width="50">{{trans('keywords.Product')}}</th>
                <th>{{trans('keywords.Title')}}</th>
                <th class="col-md-1">{{trans('keywords.Qty')}}</th>
                <th class="col-md-1">{{trans('keywords.Unit')}}</th>
                <th width="50">{{trans('keywords.Remove')}}</th>
            </tr>
        </thead>
        <tbody class="tbody-cart-items">

        </tbody>
    </table>
</div>
<div class="row">
    {{-- <div class="col-md-12">
            <i class="fa fa-comments"></i> {{trans('keywords.If you would like to see a product added to our database, please send us a detailed note in the feedback section with a subject line : New product')}}
</div> --}}
<div class="col-md-12 text-right">
    <span class="text-orange">{{trans('keywords.When you')}} <b>{{trans('keywords.type in')}}</b> {{trans('keywords.Qty, please press Enter to update cart.')}}</span>
</div>
<div class="col-md-12">
    <a class="btn btn-primary pull-right" href="javascript:void(0);" onclick="selectStore();">{{trans('keywords.\Select store')}} >></a>
    <a class="btn btn-primary pull-left" href="{{url('/contact-us')}}">{{trans("keywords.Can't find product?")}}</a>
</div>
</div>
</div>
<script type="text/javascript" src="../../../assets/frontend/js/cart.js"></script>
