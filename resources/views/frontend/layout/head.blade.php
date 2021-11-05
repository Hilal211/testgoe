<head>
    <title>Goecolo</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:500,300,700,400italic,400' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="_base_url" content="{{ url('/').'/'.App::getLocale() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
@if(env('APP_ENV')=='local')    
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'bootstrap.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'font-awesome.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'styles.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'custom.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'responsive.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'schemes/orange.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'loader/jquery.loader.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/select2/select2.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/toaster/toastr.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'datepicker/bootstrap-datepicker.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'rating/star-rating.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.COMMON_PATH').'site.css') !!}
@else
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'bootstrap.min.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'font-awesome.min.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'styles.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'custom.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'responsive.min.css?version=1') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'schemes/orange.min.css?version=1') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'loader/jquery.loader.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/select2/select2.min.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.COMMON_PATH').'plugins/toaster/toastr.min.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'datepicker/bootstrap-datepicker.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.FRONT_CSS').'rating/star-rating.min.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.COMMON_PATH').'site.css?version=1') !!}
    {{-- <link rel="stylesheet" href="https://daneden.github.io/animate.css/animate.min.css"> --}}
@endif    
    <link rel="shortcut icon" href="{{url(config('theme.ASSETS').config('theme.COMMON_PATH').'favicon.png')}}" type="image/x-icon">
</head>