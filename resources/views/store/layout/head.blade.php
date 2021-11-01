<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Goecolo | Store</title>
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="_base_url" content="{{ url('/') }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'fonts/font.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'bootstrap.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'animate.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'font-awesome.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'ionicons.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'AdminLTE.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'custom.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'skins/skin-orange.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/iCheck/all.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/select2/select2.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/toaster/toastr.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/loader/jquery.loader.css') !!}
    
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'rating/star-rating.css') !!}

    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'plugins/datatables/dataTables.bootstrap.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'plugins/morris/morris.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'plugins/jvectormap/jquery-jvectormap-1.2.2.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'plugins/datepicker/datepicker3.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') !!} 
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/file-input/css/fileinput.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.COMMON_PATH').'site.css') !!}
    <link rel="shortcut icon" href="{{url(config('theme.ASSETS').config('theme.COMMON_PATH').'favicon.png')}}" type="image/x-icon">
</head>