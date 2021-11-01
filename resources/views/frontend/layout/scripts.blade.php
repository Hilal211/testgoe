@if(env('APP_ENV')=='local')
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'jquery.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'jqueryui-1.11.2.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'bootstrap.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'loader/jquery.loader.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'datepicker/bootstrap-datepicker.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'rating/star-rating.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'icheck.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'owl-carousel.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/select2/select2.full.min.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/toaster/toaster.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/input-mask/jquery.inputmask.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'magnific.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'jstz.min.js') !!}
@else
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'jquery.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'jqueryui-1.11.2.min.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'bootstrap.min.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'loader/jquery.loader.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'datepicker/bootstrap-datepicker.min.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'rating/star-rating.min.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'icheck.min.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'owl.carousel.min.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/select2/select2.full.min.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/toaster/toaster.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/input-mask/jquery.inputmask.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'magnific.min.js') !!}
	{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'jstz.min.js') !!}
@endif

<script type="text/javascript">
	var availableProducts = {!!$ProductArr!!};
	var CurrentURL = '{{Route::getCurrentRoute()->getName()}}';
	var LogoutURL = '{{route('pages.logout')}}';
	var zip = "{{$zip}}";
	var timezone = "{{$timezone}}";
	var CartCookie = "{{$guid}}";
	var SelectPlaceHolder = "{!! $select_trans !!}";
	
	var msg ='{{trans("keywords.Opps! Looks like the items you've selected are not available in stores within a 3 miles radius.")}}';
	var NoResultsLabel = "{{trans('keywords.Sorry, No matching results found')}}";
	var MyCart = "{{trans('keywords.My Cart')}}";
	var LoginMsg = "{{trans('keywords.Login here to proceed.')}}";
	var Warning = "{{trans('keywords.Warning!')}}";
	var Success = "{{trans('keywords.Success!')}}";
	var ErrorMsg = "{{trans('keywords.Error!')}}";

	var ZipSuccessStore = "{{trans('keywords.Please confirm or change your Postal / Zip Code.')}}";
	var ZipWelcome = "{{trans('keywords.Let’s get you started. Please confirm or change your Postal / Zip Code.')}}";
	var ZipFail = "{{trans("keywords.We couldn't find your nearby Postal / Zip Code. Please enter it here.")}}";
	var InvalidZip = "{{trans("keywords.Invalid Postal / Zip Code")}}";
	var ZipLimited = "{{trans("keywords.Currently we are only serving US & Canada, your location is not from that region.")}}";
	var OldZip = "{{trans("keywords.Previously saved Postal / Zip Code code.")}}";
	var RateMsg = "{{trans("keywords.Click here to check customer comments")}}";

	var FName_msg = "{{trans("keywords.The Fistname field is required.")}}";
	var LName_msg = "{{trans("keywords.The Lastname field is required.")}}";
	var Email_msg = "{{trans("keywords.The Email field is required.")}}";
	var Phone_msg = "{{trans("keywords.The Phone field is required.")}}";
	var Zip_msg = "{{trans("keywords.The Zip field is required.")}}";
	var Address_msg = "{{trans("keywords.The Address field is required.")}}";
	var City_msg = "{{trans("keywords.The City field is required.")}}";
	var State_msg = "{{trans("keywords.The State field is required.")}}";

	var SubTotal = "{{trans("keywords.Sub Total")}}";
	var ShippingCost = "{{trans("keywords.Shipping Cost")}}";
	var Federal = "{{trans("keywords.Federal")}}";
	var Province = "{{trans("keywords.Province")}}";
	var Recycling = "{{trans("keywords.Recycling Fee")}}";
	var Grand = "{{trans("keywords.Grand Total")}}";
	var Pay = "{{trans("keywords.Pay")}}";
	var ViewItems = "{{trans("keywords.View Items")}}";

	var TypeQty = "{{trans("keywords.Type in qty here")}}";
	var AddMileMsg = "{{trans("keywords.Sorry, currently we are only serving within 3 miles radius.")}}";

	var CouponLabel1 = "{{trans("keywords.Coupon")}}";
	var CouponLabel2 = "{{trans("keywords.is applied")}}";
	var Discount = "{{trans("keywords.Discount")}}";

</script>

{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'site.js?version=1.2') !!}
{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'cart.js?version=1') !!}
{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'custom.js?version=1.1') !!}

{{-- {!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'jquery.cookie.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'ionrangeslider.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'jqzoom.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'card-payment.js') !!} --}}


@yield('page_custom_js')