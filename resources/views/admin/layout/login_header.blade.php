<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Goecolo | Log in</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'fonts/font.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'bootstrap.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'font-awesome.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'ionicons.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'AdminLTE.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_CSS').'skins/skin-orange.css') !!}
    {!! Html::style(config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/iCheck/square/blue.css') !!}
  </head>
  <body class="hold-transition login-page">
    @yield('content')
    {!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/jQuery/jQuery-2.1.4.min.js') !!}
    {!! Html::script(config('theme.ASSETS').config('theme.ADMIN_JS').'plugins/bootstrap/bootstrap.js') !!}
    {!! Html::script(config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/iCheck/icheck.min.js') !!}
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
