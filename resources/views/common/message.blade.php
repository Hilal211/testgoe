@if(Session::get('success')!='')
<div class="alert alert-success">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<h3 class="no-margin">{{trans('keywords.Thank You!')}}</h3> {{ Session::get('success') }}
</div>
@elseif(Session::get('newsletter_success')!='')
<div class="alert alert-success">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<h3 class="no-margin">{{trans('keywords.Success!')}}</h3> {{ Session::get('newsletter_success') }}
</div>
@elseif(Session::get('order_success')!='')
<div class="alert alert-custom-success">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<h3 class="no-margin">{{trans('keywords.Thank You!')}}</h3>  <span class="description">{{trans('keywords.Your order')}} {{Session::get('order_id')}} {{trans("keywords.has been placed successfully and it's being processed by the store.. Your items should be delivered to your place soon. Thanks for your business!")}}</span>
</div>
@endif