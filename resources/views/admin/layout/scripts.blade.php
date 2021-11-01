{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/jQuery/jQuery-2.1.4.min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'jquery-ui.min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'jquery.form.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/bootstrap/bootstrap.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'raphael-min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'moment.min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/sparkline/jquery.sparkline.min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/jvectormap/jquery-jvectormap-world-mill-en.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/knob/jquery.knob.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/iCheck/icheck.min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/daterangepicker/daterangepicker.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/datepicker/bootstrap-datepicker.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/datatables/jquery.dataTables.min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/datatables/dataTables.bootstrap.min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/select2/select2.full.min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/toaster/toaster.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/input-mask/jquery.inputmask.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/loader/jquery.loader.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/file-input/js/fileinput.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/slimScroll/jquery.slimscroll.min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'jstz.min.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.FRONT_JS').'rating/star-rating.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'app.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'demo.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'application.js') !!}
{!! Html::script(config('theme.ASSETS').config('theme.COMMON_PATH').'site.js?version=1.2') !!}

<script type="text/javascript">
var CurrentURL = '{{Route::getCurrentRoute()->getName()}}';
var timezone = "{{$timezone}}";
var SelectPlaceHolder = "{!! $select_trans !!}";

var Limitedmsg ='{{trans("keywords.Currently we are serving only Montreal area.")}}';
var Successmsg ='{{trans("keywords.Details saved, we'll inform you when we provide services in your city.")}}';

var Warning = "{{trans('keywords.Warning!')}}";
</script>

@if(isset($jsses))
  @foreach($jsses as $js)
    {!! Html::script($js) !!}
  @endforeach
@endif

<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
@yield('custom_js')